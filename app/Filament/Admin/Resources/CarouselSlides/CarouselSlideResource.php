<?php

namespace App\Filament\Admin\Resources\CarouselSlides;

use App\Filament\Clusters\SettingsCluster;
use BackedEnum;
use App\Filament\Admin\Resources\CarouselSlides\Pages\CreateCarouselSlide;
use App\Filament\Admin\Resources\CarouselSlides\Pages\EditCarouselSlide;
use App\Filament\Admin\Resources\CarouselSlides\Pages\ListCarouselSlides;
use App\Filament\Admin\Resources\CarouselSlides\Schemas\CarouselSlideForm;
use App\Filament\Admin\Resources\CarouselSlides\Tables\CarouselSlidesTable;
use App\Models\CarouselSlide;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CarouselSlideResource extends Resource
{
    protected static ?string $model = CarouselSlide::class;

    protected static ?string $navigationLabel = 'Homepage Carousel';

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $modelLabel = 'Carousel Slide';

    protected static ?string $pluralModelLabel = 'Carousel Slides';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinePhoto;

    public static function form(Schema $schema): Schema
    {
        return CarouselSlideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselSlidesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarouselSlides::route('/'),
            'create' => CreateCarouselSlide::route('/create'),
            'edit' => EditCarouselSlide::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('slot');
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'official'], true);
    }
}
