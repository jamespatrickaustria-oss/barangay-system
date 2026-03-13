<?php

namespace App\Filament\Admin\Resources\Residents\Pages;

use App\Filament\Admin\Resources\Residents\ResidentResource;
use Filament\Resources\Pages\ListRecords;

class ListResidents extends ListRecords
{
    protected static string $resource = ResidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateAction(),
        ];
    }
}
