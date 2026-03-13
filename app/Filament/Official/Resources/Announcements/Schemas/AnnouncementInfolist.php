<?php

namespace App\Filament\Official\Resources\Announcements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Announcement Details')
                    ->description('Basic announcement information')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title')
                            ->columnSpanFull(),
                        TextEntry::make('content')
                            ->label('Description')
                            ->columnSpanFull()
                            ->prose(),
                        ImageEntry::make('photo_path')
                            ->label('Photo')
                            ->disk('public')
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('Publication Information')
                    ->description('When the announcement was published and expires')
                    ->schema([
                        TextEntry::make('published_at')
                            ->label('Published At')
                            ->dateTime(),
                        TextEntry::make('expires_at')
                            ->label('Expires At')
                            ->dateTime(),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
                    ])->columns(2),

                Section::make('Record Information')
                    ->description('Created by user and timestamps')
                    ->schema([
                        TextEntry::make('user.first_name')
                            ->label('Created By')
                            ->formatStateUsing(fn ($state, $record) => $record->user ? $record->user->getFullName() : 'Unknown'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
