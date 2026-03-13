<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    /**
     * Display a stored user profile photo for authorized viewers.
     */
    public function show(Request $request, User $user)
    {
        $viewer = $request->user();

        abort_unless(
            $viewer && ($viewer->id === $user->id || in_array($viewer->role, ['admin', 'official'], true)),
            403
        );

        $photoPath = $user->getProfilePhotoStoragePath();

        abort_unless($photoPath && Storage::disk('public')->exists($photoPath), 404);

        return Storage::disk('public')->response($photoPath, basename($photoPath), [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}