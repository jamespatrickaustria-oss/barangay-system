<?php

namespace App\Filament\Official\Resources\CarouselSettings\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarouselSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Display Settings')
                ->schema([
                    Toggle::make('autoplay_enabled')
                        ->label('Autoplay Enabled')
                        ->inline(false),
                    TextInput::make('autoplay_speed')
                        ->label('Autoplay Speed (ms)')
                        ->numeric()
                        ->minValue(500)
                        ->default(4000),
                    Toggle::make('pause_on_hover')
                        ->label('Pause on Hover')
                        ->inline(false),
                    Toggle::make('loop')
                        ->label('Loop Slides')
                        ->inline(false),
                ])
                ->columns(2),
        ]);
    }
}
