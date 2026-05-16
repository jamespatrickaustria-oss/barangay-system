<?php

namespace App\Filament\Official\Resources\CarouselSettings;

use App\Filament\Clusters\SettingsCluster;
use BackedEnum;
use App\Filament\Official\Resources\CarouselSettings\Pages\EditCarouselSetting;
use App\Filament\Official\Resources\CarouselSettings\Pages\ListCarouselSettings;
use App\Filament\Official\Resources\CarouselSettings\Schemas\CarouselSettingForm;
use App\Filament\Official\Resources\CarouselSettings\Tables\CarouselSettingsTable;
use App\Models\CarouselSetting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CarouselSettingResource extends Resource
{
    protected static ?string $model = CarouselSetting::class;

    protected static ?string $navigationLabel = 'Carousel Settings';

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $modelLabel = 'Carousel Settings';

    protected static ?string $pluralModelLabel = 'Carousel Settings';

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineCog;

    public static function form(Schema $schema): Schema
    {
        return CarouselSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarouselSettings::route('/'),
            'edit' => EditCarouselSetting::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'official'], true);
    }
}
