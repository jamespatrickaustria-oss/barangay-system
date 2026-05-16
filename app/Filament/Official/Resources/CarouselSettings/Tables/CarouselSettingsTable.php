<?php

namespace App\Filament\Official\Resources\CarouselSettings\Tables;

use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;

class CarouselSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                BooleanColumn::make('autoplay_enabled')->label('Autoplay'),
                TextColumn::make('autoplay_speed')->label('Speed (ms)'),
                BooleanColumn::make('pause_on_hover')->label('Pause on Hover'),
                BooleanColumn::make('loop')->label('Loop'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
