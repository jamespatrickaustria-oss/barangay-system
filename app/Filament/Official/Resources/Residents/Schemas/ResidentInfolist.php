<?php

namespace App\Filament\Official\Resources\Residents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Schemas\Schema;

class ResidentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Resident personal details')
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('First Name'),
                        TextEntry::make('middle_name')
                            ->label('Middle Name')
                            ->placeholder('—'),
                        TextEntry::make('last_name')
                            ->label('Last Name'),
                        TextEntry::make('email')
                            ->label('Email Address'),
                        TextEntry::make('phone')
                            ->label('Phone Number'),
                        TextEntry::make('birthdate')
                            ->label('Birthdate')
                            ->date(),
                    ])->columns(2),

                Section::make('Additional Information')
                    ->description('Resident status and occupation details')
                    ->schema([
                        TextEntry::make('gender')
                            ->label('Gender')
                            ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                                null => 'Not specified',
                            }),
                        TextEntry::make('civil_status')
                            ->label('Civil Status'),
                        TextEntry::make('occupation')
                            ->label('Occupation'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                'pending' => 'warning',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    ])->columns(2),

                Section::make('Address and Dates')
                    ->description('Resident location and record dates')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
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
