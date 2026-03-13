<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandLogo = file_exists(public_path('images/city_of_general_trias_seal.png'))
            ? asset('images/city_of_general_trias_seal.png')
            : (file_exists(public_path('images/city_of_general trias.png'))
                ? asset('images/city_of_general trias.png')
                : asset('images/city_of_general_trias.png'));

        return $panel
            ->id('admin')
            ->path('admin')
            ->responsive()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->collapsedSidebarWidth('4rem')
            ->brandLogo($brandLogo)
            ->darkModeBrandLogo($brandLogo)
            ->brandLogoHeight('2.5rem')
            ->favicon($brandLogo)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.admin.custom-styles')
            )
            ->pages([
                \App\Filament\Admin\Pages\Dashboard::class,
            ])
            ->resources([
                \App\Filament\Admin\Resources\UserApprovalResource::class,
                \App\Filament\Admin\Resources\Users\UserResource::class,
                \App\Filament\Admin\Resources\Residents\ResidentResource::class,
                \App\Filament\Admin\Resources\Announcements\AnnouncementResource::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->login();
    }
}
