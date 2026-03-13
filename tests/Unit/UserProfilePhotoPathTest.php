<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfilePhotoPathTest extends TestCase
{
    public function test_it_resolves_current_profile_photo_storage_paths(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/profile_photos/current.jpg', 'photo');

        $user = new User([
            'profile_photo' => 'uploads/profile_photos/current.jpg',
        ]);

        $this->assertSame('uploads/profile_photos/current.jpg', $user->getProfilePhotoStoragePath());
    }

    public function test_it_resolves_legacy_basename_profile_photo_storage_paths(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/legacy.jpg', 'photo');

        $user = new User([
            'profile_photo' => 'legacy.jpg',
        ]);

        $this->assertSame('photos/legacy.jpg', $user->getProfilePhotoStoragePath());
    }
}