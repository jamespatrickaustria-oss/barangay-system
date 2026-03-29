<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatThread;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function __construct(private readonly ImageUploadService $imageUploadService)
    {
    }

    public function officialIndex()
    {
        return view('official.chat.index');
    }

    public function officialResidents(): JsonResponse
    {
        $residents = User::query()
            ->where('role', 'resident')
            ->where('status', 'approved')
            ->with([
                'chatThread.latestMessage' => function ($query) {
                    $query->with('sender:id,first_name,middle_name,surname,role');
                },
            ])
            ->orderBy('surname')
            ->orderBy('first_name')
            ->get();

        return response()->json([
            'residents' => $residents->map(function (User $resident) {
                $thread = $resident->chatThread;
                $latest = $thread?->latestMessage;
                $lastMessageAt = $thread?->last_message_at ?? $thread?->updated_at;

                return [
                    'id' => $resident->id,
                    'resident_name' => $resident->getFullName(),
                    'resident_email' => $resident->email,
                    'thread_id' => $thread?->id,
                    'last_message_at' => $lastMessageAt?->setTimezone('Asia/Manila')->toDateTimeString(),
                    'last_message_at_human' => $lastMessageAt?->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                    'latest_preview' => $latest?->body ?: ($latest?->image_path ? 'Image attachment' : 'No messages yet'),
                ];
            })->values(),
        ]);
    }

    public function officialResidentThread(Request $request, User $resident): JsonResponse
    {
        if ($resident->role !== 'resident' || $resident->status !== 'approved') {
            abort(404);
        }

        $thread = ChatThread::firstOrCreate(
            ['resident_id' => $resident->id],
            ['last_message_at' => now()]
        );

        $messages = $thread->messages()
            ->with('sender:id,first_name,middle_name,surname,role')
            ->limit(100)
            ->get();

        return response()->json([
            'thread_id' => $thread->id,
            'resident_name' => $resident->getFullName(),
            'messages' => $messages->map(fn (ChatMessage $message) => $this->formatMessage($message, $request->user()->id)),
        ]);
    }

    public function residentThread(Request $request): JsonResponse
    {
        $resident = $request->user();

        $thread = ChatThread::firstOrCreate(
            ['resident_id' => $resident->id],
            ['last_message_at' => now()]
        );

        $messages = $thread->messages()
            ->with('sender:id,first_name,middle_name,surname,role')
            ->limit(100)
            ->get();

        return response()->json([
            'thread_id' => $thread->id,
            'messages' => $messages->map(fn (ChatMessage $message) => $this->formatMessage($message, $resident->id)),
        ]);
    }

    public function residentMessages(Request $request): JsonResponse
    {
        $thread = ChatThread::where('resident_id', $request->user()->id)->firstOrFail();

        $afterId = (int) $request->query('after_id', 0);

        $query = $thread->messages()->with('sender:id,first_name,middle_name,surname,role');

        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->limit(100)->get();

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $message) => $this->formatMessage($message, $request->user()->id)),
        ]);
    }

    public function residentSend(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'nullable|string|max:2000',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (!$request->filled('body') && !$request->hasFile('image')) {
            return response()->json(['message' => 'Message text or image is required.'], 422);
        }

        $resident = $request->user();

        $thread = ChatThread::firstOrCreate(
            ['resident_id' => $resident->id],
            ['last_message_at' => now()]
        );

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->imageUploadService->store($request->file('image'), 'uploads/chat_images');
        }

        $message = ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'sender_id' => $resident->id,
            'body' => $validated['body'] ?? null,
            'image_path' => $imagePath,
        ]);

        $thread->update(['last_message_at' => now()]);

        $message->load('sender:id,first_name,middle_name,surname,role');

        return response()->json([
            'message' => $this->formatMessage($message, $resident->id),
        ]);
    }

    public function officialThreads(): JsonResponse
    {
        $threads = ChatThread::with([
            'resident:id,first_name,middle_name,surname,email',
            'latestMessage' => function ($query) {
                $query->with('sender:id,first_name,middle_name,surname,role');
            },
        ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'threads' => $threads->map(function (ChatThread $thread) {
                $latest = $thread->latestMessage;

                return [
                    'id' => $thread->id,
                    'resident_name' => $thread->resident?->getFullName() ?? 'Resident',
                    'resident_email' => $thread->resident?->email,
                    'last_message_at' => optional($thread->last_message_at ?? $thread->updated_at)->setTimezone('Asia/Manila')->toDateTimeString(),
                    'last_message_at_human' => optional($thread->last_message_at ?? $thread->updated_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                    'latest_preview' => $latest?->body ?: ($latest?->image_path ? '📷 Image' : 'No messages yet'),
                ];
            }),
        ]);
    }

    public function officialMessages(Request $request, ChatThread $thread): JsonResponse
    {
        $afterId = (int) $request->query('after_id', 0);

        $query = $thread->messages()->with('sender:id,first_name,middle_name,surname,role');

        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->limit(100)->get();

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $message) => $this->formatMessage($message, $request->user()->id)),
            'resident_name' => $thread->resident?->getFullName() ?? 'Resident',
        ]);
    }

    public function officialSend(Request $request, ChatThread $thread): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'nullable|string|max:2000',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (!$request->filled('body') && !$request->hasFile('image')) {
            return response()->json(['message' => 'Message text or image is required.'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->imageUploadService->store($request->file('image'), 'uploads/chat_images');
        }

        $message = ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'sender_id' => $request->user()->id,
            'body' => $validated['body'] ?? null,
            'image_path' => $imagePath,
        ]);

        $thread->update(['last_message_at' => now()]);

        $message->load('sender:id,first_name,middle_name,surname,role');

        return response()->json([
            'message' => $this->formatMessage($message, $request->user()->id),
        ]);
    }

    public function markMessagesAsRead(Request $request, ChatThread $thread): JsonResponse
    {
        // Get messages that are not sent by the current user and mark them as read
        $thread->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function residentUnreadCount(Request $request): JsonResponse
    {
        $resident = $request->user();
        
        $thread = ChatThread::where('resident_id', $resident->id)->first();
        
        $unreadCount = 0;
        if ($thread) {
            $unreadCount = $thread->messages()
                ->where('sender_id', '!=', $resident->id)
                ->where('is_read', false)
                ->count();
        }

        return response()->json(['unread_count' => $unreadCount]);
    }

    public function officialUnreadCount(): JsonResponse
    {
        $threads = ChatThread::with('messages')->get();
        
        $totalUnread = 0;
        foreach ($threads as $thread) {
            $totalUnread += $thread->messages()
                ->where('sender_id', '!=', auth()->id())
                ->where('is_read', false)
                ->count();
        }

        return response()->json(['unread_count' => $totalUnread]);
    }

    private function formatMessage(ChatMessage $message, int $viewerId): array
    {
        return [
            'id' => $message->id,
            'body' => $message->body,
            'image_url' => $message->image_path ? Storage::url($message->image_path) : null,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender?->getFullName() ?? 'User',
            'sender_role' => $message->sender?->role,
            'is_mine' => $message->sender_id === $viewerId,
            'is_read' => $message->is_read,
            'created_at' => $message->created_at?->setTimezone('Asia/Manila')->toDateTimeString(),
            'created_at_human' => $message->created_at?->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
        ];
    }
}
