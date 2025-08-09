<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Promethys\Revive\RevivePlugin;
use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use FilamentWebpush\FilamentWebpushPlugin;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use Awcodes\LightSwitch\LightSwitchPlugin;
use TomatoPHP\FilamentPWA\FilamentPWAPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->plugins(plugins: [
                // FilamentOtpLoginPlugin::make(),
                // GlobalSearchModalPlugin::make()
                // ->closeButton(enabled: true)
                // ->localStorageMaxItemsAllowed(50),

                FilamentShieldPlugin::make(),

                BreezyCore::make()
                    // ->customMyProfilePage(AccountSettingsPage::class)
                    ->enableTwoFactorAuthentication()
                    ->myProfile(),
                RevivePlugin::make(),
                FilamentWebpushPlugin::make(),

                ResizedColumnPlugin::make()
                    ->preserveOnDB(),
                LightSwitchPlugin::make(),

                FilamentPWAPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications();
    }
}
