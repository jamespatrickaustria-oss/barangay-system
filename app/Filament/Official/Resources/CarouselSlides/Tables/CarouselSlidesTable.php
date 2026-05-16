<?php

namespace App\Filament\Official\Resources\CarouselSlides\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CarouselSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('slot')
            ->reorderable('slot')
            ->columns([
                TextColumn::make('slot')
                    ->label('Slot')
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->height(80),
                TextColumn::make('title')
                    ->label('Title')
                    ->limit(40)
                    ->placeholder('No title')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->placeholder('No description'),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable(),
                ToggleColumn::make('enabled')
                    ->label('Enabled')
                    ->sortable(),
                TextColumn::make('link_url')
                    ->label('Link')
                    ->limit(40)
                    ->placeholder('—'),
            ])
            ->bulkActions([
                BulkAction::make('enable')
                    ->label('Enable selected')
                    ->action(function ($records) {
                        $records->each->update(['enabled' => true]);
                    })
                    ->icon('heroicon-s-check'),
                BulkAction::make('disable')
                    ->label('Disable selected')
                    ->action(function ($records) {
                        $records->each->update(['enabled' => false]);
                    })
                    ->icon('heroicon-s-x'),
            ])
            ->recordActions([
                Action::make('move_up')
                    ->label('Move Up')
                    ->icon('heroicon-s-arrow-up')
                    ->action(function ($record, $data, $livewire) {
                        $prev = \App\Models\CarouselSlide::where('slot', '<', $record->slot)->orderByDesc('slot')->first();
                        if ($prev) {
                            $temp = $prev->slot;
                            $prev->slot = $record->slot;
                            $record->slot = $temp;
                            $prev->save();
                            $record->save();
                        }
                    }),
                Action::make('move_down')
                    ->label('Move Down')
                    ->icon('heroicon-s-arrow-down')
                    ->action(function ($record) {
                        $next = \App\Models\CarouselSlide::where('slot', '>', $record->slot)->orderBy('slot')->first();
                        if ($next) {
                            $temp = $next->slot;
                            $next->slot = $record->slot;
                            $record->slot = $temp;
                            $next->save();
                            $record->save();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
