<?php

namespace App\Filament\Official\Resources\Residents;

use App\Filament\Official\Resources\Residents\Pages\CreateResident;
use App\Filament\Official\Resources\Residents\Pages\EditResident;
use App\Filament\Official\Resources\Residents\Pages\ListResidents;
use App\Filament\Official\Resources\Residents\Pages\ViewResident;
use App\Filament\Official\Resources\Residents\Schemas\ResidentForm;
use App\Filament\Official\Resources\Residents\Schemas\ResidentInfolist;
use App\Filament\Official\Resources\Residents\Tables\ResidentsTable;
use App\Models\Resident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationLabel = 'Residents';

    protected static ?string $modelLabel = 'Resident';

    protected static ?string $pluralModelLabel = 'Residents';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResidents::route('/'),
            'create' => CreateResident::route('/create'),
            'view' => ViewResident::route('/{record}'),
            'edit' => EditResident::route('/{record}/edit'),
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
        if (!auth()->check()) {
            return false;
        }

        return in_array(auth()->user()->role, ['admin', 'official'], true);
    }
}
