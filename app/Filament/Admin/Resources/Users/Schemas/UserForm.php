<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->description('Basic account details for the official')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->maxLength(255),
                        TextInput::make('surname')
                            ->label('Surname')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'users',
                                column: 'email',
                                ignoreRecord: true,
                                modifyRuleUsing: fn ($rule) => $rule->whereNull('deleted_at'),
                            ),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->minLength(8),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                    ])->columns(2),

                Section::make('Personal Information')
                    ->description('Additional personal details')
                    ->schema([
                        DatePicker::make('birthdate')
                            ->label('Birthdate'),
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                        TextInput::make('address')
                            ->label('Address')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Account Status')
                    ->description('Control account approval status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required()
                            ->helperText('Officials must verify their email before accessing the system. Status will automatically update to "Approved" after email verification.'),
                    ]),
            ]);
    }
}
