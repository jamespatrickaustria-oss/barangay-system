<?php

namespace App\Filament\Official\Resources\CarouselSettings\Pages;

use App\Filament\Official\Resources\CarouselSettings\CarouselSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditCarouselSetting extends EditRecord
{
    protected static string $resource = CarouselSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
