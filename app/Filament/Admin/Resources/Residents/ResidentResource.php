<?php

namespace App\Filament\Admin\Resources\Residents;

use App\Models\Resident;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Official\Resources\Residents\Schemas\ResidentForm;
use App\Filament\Official\Resources\Residents\Schemas\ResidentInfolist;
use App\Filament\Official\Resources\Residents\Tables\ResidentsTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationLabel = 'Residents';

    protected static ?int $navigationSort = 2;

    protected static string|null $navigationIcon = Heroicon::OutlineUsers;

    public static function form(Schema $schema): Schema
    {
        return ResidentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResidentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResidentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\Residents\Pages\ListResidents::route('/'),
            'create' => \App\Filament\Admin\Resources\Residents\Pages\CreateResident::route('/create'),
            'view' => \App\Filament\Admin\Resources\Residents\Pages\ViewResident::route('/{record}'),
            'edit' => \App\Filament\Admin\Resources\Residents\Pages\EditResident::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'official']);
    }
}
