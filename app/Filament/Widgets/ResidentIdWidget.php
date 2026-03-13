<?php

namespace App\Filament\Widgets;

use App\Models\OnlineId;
use App\Models\Resident;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ResidentIdWidget extends BaseWidget
{
    protected static ?string $heading = 'Resident IDs';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OnlineId::query()
                    ->with('user')
                    ->latest('issued_at')
            )
            ->columns([
                TextColumn::make('user.account_number')
                    ->label('Account No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_name')
                    ->label('Resident Name')
                    ->getStateUsing(fn (OnlineId $record): string =>
                        $record->user
                            ? trim($record->user->first_name . ' ' . ($record->user->middle_name ? $record->user->middle_name . ' ' : '') . $record->user->surname)
                            : '—'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function (Builder $q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('surname', 'like', "%{$search}%")
                              ->orWhere('middle_name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('issued_at')
                    ->label('Date Issued')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('issued_at', 'desc')
            ->emptyStateHeading('No resident IDs issued yet')
            ->emptyStateDescription('Resident IDs will appear here once issued.');
    }
}
