<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Andreia\FilamentNordTheme\FilamentNordThemePlugin;
use Filament\Support\Assets\Css; // Kita tidak butuh ini lagi, tapi tidak apa-apa jika masih ada

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('admin')
            ->login()
            ->brandName('Koperasi SMKN 8 Jakarta')
            // ->brandLogo(asset('images/logo.jpg'))
            ->colors([
                'primary' => Color::Indigo, // Coba ganti jadi: Emerald, Rose, Blue, Violet, dll
                'gray' => Color::Slate,     // Coba ganti jadi: Gray, Zinc, Neutral, Stone
            ])
            ->font('Poppins') // Ganti font ke Google Font lain (misal: 'Inter', 'Quicksand')
                        
            // [PERBAIKAN] Ganti '->assets()' dengan '->viteTheme()'
            // Ini adalah cara yang benar untuk memuat CSS kustom
            ->viteTheme('resources/css/filament-admin.css')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentNordThemePlugin::make(),
            ]);
    }
}