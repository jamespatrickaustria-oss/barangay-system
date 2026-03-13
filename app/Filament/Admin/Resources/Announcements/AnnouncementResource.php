<?php

namespace App\Filament\Admin\Resources\Announcements;

use App\Models\Announcement;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Official\Resources\Announcements\Schemas\AnnouncementForm;
use App\Filament\Official\Resources\Announcements\Schemas\AnnouncementInfolist;
use App\Filament\Official\Resources\Announcements\Tables\AnnouncementsTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationLabel = 'Announcements';

    protected static ?int $navigationSort = 3;

    protected static string|null $navigationIcon = Heroicon::OutlineSpeakerphone;

    public static function form(Schema $schema): Schema
    {
        return AnnouncementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AnnouncementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnnouncementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\Announcements\Pages\ListAnnouncements::route('/'),
            'create' => \App\Filament\Admin\Resources\Announcements\Pages\CreateAnnouncement::route('/create'),
            'view' => \App\Filament\Admin\Resources\Announcements\Pages\ViewAnnouncement::route('/{record}'),
            'edit' => \App\Filament\Admin\Resources\Announcements\Pages\EditAnnouncement::route('/{record}/edit'),
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
