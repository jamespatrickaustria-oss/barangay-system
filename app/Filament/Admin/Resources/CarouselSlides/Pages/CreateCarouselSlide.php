<?php

namespace App\Filament\Admin\Resources\CarouselSlides\Pages;

use App\Filament\Admin\Resources\CarouselSlides\CarouselSlideResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarouselSlide extends CreateRecord
{
    protected static string $resource = CarouselSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
