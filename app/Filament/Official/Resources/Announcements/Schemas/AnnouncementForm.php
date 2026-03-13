<?php

namespace App\Filament\Official\Resources\Announcements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Announcement Details')
                    ->description('Create a community update visible to residents')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('content')
                            ->label('Description')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                        FileUpload::make('photo_path')
                            ->label('Photo')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/announcement_photos')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Publication Settings')
                    ->schema([
                        DatePicker::make('expires_at')
                            ->label('Expiration Date')
                            ->helperText('Optional. Announcement remains visible through this date.')
                            ->minDate(today())
                            ->native(false),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
