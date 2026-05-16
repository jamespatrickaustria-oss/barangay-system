<?php

namespace App\Filament\Admin\Resources\CarouselSlides\Pages;

use App\Filament\Admin\Resources\CarouselSlides\CarouselSlideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarouselSlide extends EditRecord
{
    protected static string $resource = CarouselSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
