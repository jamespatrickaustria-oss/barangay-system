<?php

namespace App\Filament\Official\Resources\CarouselSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CarouselSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slide Details')
                    ->description('Upload and manage one homepage carousel slide. Files are stored on the server, not in the database.')
                    ->schema([
                        TextInput::make('slot')
                            ->label('Slide Slot')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        FileUpload::make('image_path')
                            ->label('Slide Image')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/carousel')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->rules(['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'])
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');

                                return 'carousel_' . now()->format('YmdHis') . '_' . Str::uuid()->toString() . '.' . $extension;
                            })
                            ->maxSize(5120)
                            ->imageEditor()
                            ->helperText('Accepted file types: JPG, PNG, WEBP. Maximum size: 5 MB.')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->label('Slide Title')
                            ->maxLength(120)
                            ->nullable(),
                        TextInput::make('link_url')
                            ->label('Link URL')
                            ->url()
                            ->nullable()
                            ->helperText('Optional URL to open when the slide is clicked.'),
                        Toggle::make('open_in_new_tab')
                            ->label('Open link in new tab')
                            ->inline(false)
                            ->columnSpanFull(),
                        Toggle::make('enabled')
                            ->label('Enabled')
                            ->inline(false)
                            ->default(true),
                        Textarea::make('description')
                            ->label('Slide Description')
                            ->rows(3)
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
