<?php

namespace App\Filament\Admin\Resources\CarouselSettings\Pages;

use App\Filament\Admin\Resources\CarouselSettings\CarouselSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarouselSetting extends EditRecord
{
    protected static string $resource = CarouselSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
