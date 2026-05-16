<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public const BASE_DIRECTORY = 'uploads';

    /**
     * Store an uploaded image on the public disk with a unique name.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $normalizedDirectory = trim($directory, '/');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = now()->format('YmdHis') . '_' . Str::uuid()->toString() . '.' . $extension;

        return $file->storeAs($normalizedDirectory, $filename, 'public');
    }

    /**
     * Delete a file from public storage if it exists.
     */
    public function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * Ensure base upload directories exist.
     */
    public function ensureDirectoriesExist(): void
    {
        $disk = Storage::disk('public');

        foreach ($this->directories() as $directory) {
            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function directories(): array
    {
        return [
            self::BASE_DIRECTORY,
            self::BASE_DIRECTORY . '/profile_photos',
            self::BASE_DIRECTORY . '/announcement_photos',
            self::BASE_DIRECTORY . '/chat_images',
            self::BASE_DIRECTORY . '/chat-images',
            self::BASE_DIRECTORY . '/carousel',
        ];
    }
}
