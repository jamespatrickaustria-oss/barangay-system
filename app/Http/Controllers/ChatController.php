<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatThread;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function officialIndex()
    {
        return view('official.chat.index');
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
            $imagePath = $request->file('image')->store('chat-images', 'public');
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (!$request->filled('body') && !$request->hasFile('image')) {
            return response()->json(['message' => 'Message text or image is required.'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat-images', 'public');
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
            'created_at' => $message->created_at?->setTimezone('Asia/Manila')->toDateTimeString(),
            'created_at_human' => $message->created_at?->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
        ];
    }
}
