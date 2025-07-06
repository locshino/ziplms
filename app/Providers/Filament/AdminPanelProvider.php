<?php

namespace App\Providers\Filament;

use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use App\Filament\Plugins\FilamentProgressbarPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->login(OtpLogin::class)
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages($this->pages())
            ->widgets($this->widgets())
            ->middleware($this->middleware())
            ->authMiddleware($this->authMiddleware())
            ->plugins($this->plugins())
            ->databaseNotifications();
    }

    protected function middleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }

    protected function authMiddleware(): array
    {
        return [
            Authenticate::class,
        ];
    }

    protected function pages(): array
    {
        return [
            Pages\Dashboard::class,
            \App\Filament\Pages\ManageGeneralSettings::class,
            //
        ];
    }

    protected function widgets(): array
    {
        return [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ];
    }

    protected function plugins(): array
    {
        return [
            FilamentOtpLoginPlugin::make(),
            SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['vi', 'en']),
            FilamentSpatieLaravelBackupPlugin::make(),
            FilamentSpatieLaravelHealthPlugin::make(),
            FilamentProgressbarPlugin::make(),
        ];
    }
}
