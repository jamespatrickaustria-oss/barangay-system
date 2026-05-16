<?php

namespace App\Filament\Admin\Resources\CarouselSettings\Pages;

use App\Filament\Admin\Resources\CarouselSettings\CarouselSettingResource;
use Filament\Resources\Pages\ListRecords;

class ListCarouselSettings extends ListRecords
{
    protected static string $resource = CarouselSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
