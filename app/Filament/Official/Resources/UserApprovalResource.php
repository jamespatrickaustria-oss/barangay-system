<?php

namespace App\Filament\Official\Resources;

use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class UserApprovalResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'User Approvals';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static string $navigationIcon = Heroicon::OutlineCheckCircle;

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->disabled(),
                        TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->disabled(),
                        TextInput::make('surname')
                            ->label('Surname')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->disabled(),
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->disabled(),
                    ]),
                Section::make('Request Details')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->label('Requested Position')
                            ->options([
                                'resident' => 'Resident',
                            ])
                            ->disabled(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->native(false),
                    ]),
                Section::make('Address Information')
                    ->columns(3)
                    ->schema([
                        TextInput::make('house_no')
                            ->label('House No#')
                            ->disabled(),
                        TextInput::make('barangay')
                            ->label('Barangay')
                            ->disabled(),
                        TextInput::make('municipality_city')
                            ->label('Municipality/City')
                            ->disabled(),
                    ]),
                Section::make('Residency Verification')
                    ->description('Review the submitted registration photo and personal details before approving or rejecting the request.')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('registration_photo_preview')
                            ->label('Submitted Registration Photo')
                            ->content(fn (?User $record): HtmlString => new HtmlString(
                                $record?->profile_photo_url
                                    ? '<div style="max-width: 280px;"><img src="' . e($record->profile_photo_url) . '" alt="Submitted registration photo" style="display:block;width:100%;height:auto;border-radius:12px;border:1px solid #d1d5db;object-fit:cover;"></div>'
                                    : '<span style="color:#6b7280;">No registration photo uploaded.</span>'
                            )),
                        Placeholder::make('verification_summary')
                            ->label('Verification Summary')
                            ->content(fn (?User $record): HtmlString => new HtmlString(
                                $record
                                    ? '<div style="display:grid;gap:8px;">'
                                        . '<div><strong>Submitted:</strong> ' . e(optional($record->created_at)->format('M d, Y h:i A') ?? 'N/A') . '</div>'
                                        . '<div><strong>Birthdate:</strong> ' . e(optional($record->birthdate)->format('F d, Y') ?? 'N/A') . '</div>'
                                        . '<div><strong>Marital Status:</strong> ' . e($record->marital_status ? ucfirst($record->marital_status) : 'N/A') . '</div>'
                                        . '<div><strong>Nationality:</strong> ' . e($record->nationality ?: 'N/A') . '</div>'
                                        . '<div><strong>Father:</strong> ' . e($record->father_name ?: 'N/A') . '</div>'
                                        . '<div><strong>Mother:</strong> ' . e($record->mother_name ?: 'N/A') . '</div>'
                                        . '<div><strong>Address:</strong> ' . e($record->address ?: 'N/A') . '</div>'
                                    . '</div>'
                                    : '<span style="color:#6b7280;">User record is not available.</span>'
                            )),
                    ]),
                Section::make('Digital ID Preview')
                    ->description('Auto-fetched from registration profile data')
                    ->schema([
                        Placeholder::make('digital_id_preview')
                            ->label('')
                            ->content(fn (?User $record): HtmlString => new HtmlString(
                                $record
                                    ? view('partials.digital-id-card', [
                                        'user' => $record,
                                        'onlineId' => $record->onlineId,
                                    ])->render()
                                    : '<p>User record is not available.</p>'
                            )),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('Full Name')
                    ->formatStateUsing(fn ($record) => $record->getFullName())
                    ->searchable(['first_name', 'middle_name', 'surname'])
                    ->sortable('first_name'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Requested Position')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'resident' => 'Resident',
                        'official' => 'Barangay Official',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'resident' => 'info',
                        'official' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Applied Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->form([
                        Textarea::make('approval_note')
                            ->label('Approval Note (Optional)')
                            ->rows(3),
                    ])
                    ->action(function (User $record, array $data) {
                        $currentUser = auth()->user();
                        
                        // Barangay Officials can only approve residents
                        if ($record->role !== 'resident') {
                            throw new \Exception('Barangay Officials can only approve Resident registrations.');
                        }

                        // Update user status and approval tracking
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => $currentUser->id,
                            'approved_at' => now(),
                            'approver_role' => $currentUser->role,
                        ]);

                        // Generate resident ID and create online ID if resident is being approved
                        if (!$record->resident_id_number) {
                            $residentIdNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . $record->id;
                            $record->update(['resident_id_number' => $residentIdNumber]);

                            // Create OnlineId record
                            \App\Models\OnlineId::firstOrCreate(
                                ['user_id' => $record->id],
                                [
                                    'id_number' => \App\Models\OnlineId::generateIdNumber(),
                                    'issued_at' => now(),
                                ]
                            );
                        }

                        // Send notification to the approved user
                        \App\Models\Notification::create([
                            'user_id' => $record->id,
                            'sender_id' => $currentUser->id,
                            'title' => 'Registration Approved',
                            'message' => 'Your registration has been approved by ' . $currentUser->getFullName() . '. You can now access the system as a Resident.',
                        ]);

                        // Send email notification (optional)
                        try {
                            \App\Services\MailService::send(
                                $record->email,
                                $record->getFullName(),
                                'Registration Approved',
                                'Your registration has been approved. You can now log in to the system.'
                            );
                        } catch (\Exception $e) {
                            // Log email failure but don't stop the approval process
                            logger()->error('Failed to send approval email: ' . $e->getMessage());
                        }
                    })
                    ->visible(fn (User $record) => $record->status === 'pending' && $record->role === 'resident')
                    ->successNotificationTitle('User Approved'),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (User $record, array $data) {
                        $currentUser = auth()->user();
                        
                        // Barangay Officials can only reject residents
                        if ($record->role !== 'resident') {
                            throw new \Exception('Barangay Officials can only reject Resident registrations.');
                        }

                        // Update user status and rejection tracking
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => $currentUser->id,
                            'approved_at' => now(),
                            'approver_role' => $currentUser->role,
                        ]);

                        // Send notification to the rejected user
                        \App\Models\Notification::create([
                            'user_id' => $record->id,
                            'sender_id' => $currentUser->id,
                            'title' => 'Registration Rejected',
                            'message' => 'Your registration has been rejected. Reason: ' . ($data['rejection_reason'] ?? 'Not specified'),
                        ]);

                        // Send email notification (optional)
                        try {
                            \App\Services\MailService::send(
                                $record->email,
                                $record->getFullName(),
                                'Registration Rejected',
                                'Your registration has been rejected. Reason: ' . ($data['rejection_reason'] ?? 'Not specified')
                            );
                        } catch (\Exception $e) {
                            // Log email failure but don't stop the rejection process
                            logger()->error('Failed to send rejection email: ' . $e->getMessage());
                        }
                    })
                    ->visible(fn (User $record) => $record->status === 'pending' && $record->role === 'resident')
                    ->successNotificationTitle('User Rejected'),
            ])
            ->bulkActions([
                // Bulk actions for multiple users
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Filament\Resources\Pages\ListRecords::route('/'),
            'view' => \Filament\Resources\Pages\ViewRecord::route('/{record}'),
            'edit' => \Filament\Resources\Pages\EditRecords::route('/{record}/edit'),
        ];
    }

    /**
     * Filter to show only pending residents and officials
     */
    public static function getEloquentQuery(): Builder
    {
        // Barangay Officials can only see resident registrations
        return parent::getEloquentQuery()
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->where('role', 'resident');
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'official';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
