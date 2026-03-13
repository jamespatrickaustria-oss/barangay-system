<?php

namespace App\Filament\Admin\Resources\Announcements\Pages;

use App\Filament\Admin\Resources\Announcements\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['published_at'] = now();
        $data['is_active'] = $data['is_active'] ?? true;

        if (!empty($data['expires_at'])) {
            $data['expires_at'] = \Illuminate\Support\Carbon::parse($data['expires_at'])->endOfDay();
        }

        return $data;
    }
}
