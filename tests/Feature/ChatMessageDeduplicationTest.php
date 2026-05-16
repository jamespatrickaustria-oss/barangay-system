<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatMessageDeduplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_chat_send_processes_same_submission_only_once(): void
    {
        Storage::fake('public');

        $resident = User::create([
            'first_name' => 'Maria',
            'middle_name' => null,
            'surname' => 'Santos',
            'email' => 'maria.santos@example.com',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'status' => 'approved',
        ]);

        $this->actingAs($resident);

        $clientMessageId = '550e8400-e29b-41d4-a716-446655440000';

        $firstResponse = $this->post(route('resident.chat.send'), [
            'body' => 'Hello from the resident',
            'image' => UploadedFile::fake()->create('chat.jpg', 100, 'image/jpeg'),
            'client_message_id' => $clientMessageId,
        ]);

        $firstResponse->assertOk();

        $secondResponse = $this->post(route('resident.chat.send'), [
            'body' => 'Hello from the resident',
            'image' => UploadedFile::fake()->create('chat.jpg', 100, 'image/jpeg'),
            'client_message_id' => $clientMessageId,
        ]);

        $secondResponse->assertOk();
        $secondResponse->assertJsonPath('message.id', $firstResponse->json('message.id'));

        $this->assertDatabaseCount('chat_messages', 1);

        $message = ChatMessage::query()->firstOrFail();
        $this->assertSame($clientMessageId, $message->client_message_id);
        Storage::disk('public')->assertExists($message->image_path);
        $this->assertCount(1, Storage::disk('public')->allFiles('uploads/chat-images'));
    }
}