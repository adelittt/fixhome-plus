<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StorePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('toko')
            ->path('toko')
            ->brandName('FixHome Plus - Mitra Toko')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => [
                    50 => '#F2FAF5',
                    100 => '#DFF2E6',
                    200 => '#BFE3CC',
                    300 => '#85C99E',
                    400 => '#5DBA81',
                    500 => '#3FAE6D',
                    600 => '#2E8B57',
                    700 => '#1F6B3E',
                    800 => '#175430',
                    900 => '#14432A',
                    950 => '#0F2A1A',
                ],
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->font('Inter')
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Store/Resources'), for: 'App\\Filament\\Store\\Resources')
            ->discoverPages(in: app_path('Filament/Store/Pages'), for: 'App\\Filament\\Store\\Pages')
            ->pages([
                Pages\Dashboard::class,
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
                \App\Http\Middleware\FilamentAuthenticate::class,
            ]);
    }
}
