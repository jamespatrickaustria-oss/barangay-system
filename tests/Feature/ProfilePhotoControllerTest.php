<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_a_resident_profile_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/profile_photos/resident.jpg', 'resident-photo');

        $resident = User::create([
            'first_name' => 'Juan',
            'surname' => 'Dela Cruz',
            'email' => 'resident@example.com',
            'password' => Hash::make('password123'),
            'role' => 'resident',
            'status' => 'approved',
            'profile_photo' => 'uploads/profile_photos/resident.jpg',
        ]);

        $admin = User::create([
            'first_name' => 'Maria',
            'surname' => 'Santos',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get(route('profile-photos.show', $resident));

        $response->assertOk();
        $cacheControl = (string) $response->headers->get('cache-control');

        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('max-age=3600', $cacheControl);
        $this->assertSame('resident-photo', $response->streamedContent());
    }

    public function test_resident_cannot_view_another_users_profile_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/profile_photos/resident.jpg', 'resident-photo');

        $resident = User::create([
            'first_name' => 'Juan',
            'surname' => 'Dela Cruz',
            'email' => 'resident@example.com',
            'password' => Hash::make('password123'),
            'role' => 'resident',
            'status' => 'approved',
            'profile_photo' => 'uploads/profile_photos/resident.jpg',
        ]);

        $otherResident = User::create([
            'first_name' => 'Ana',
            'surname' => 'Reyes',
            'email' => 'other-resident@example.com',
            'password' => Hash::make('password123'),
            'role' => 'resident',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($otherResident)->get(route('profile-photos.show', $resident));

        $response->assertForbidden();
    }
}