<?php

namespace App\Providers\Filament;

use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use App\Filament\Plugins\FilamentProgressbarPlugin;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use Avexsoft\FilamentPurl\FilamentPurlPlugin;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Widgets;
use FilamentWebpush\FilamentWebpushPlugin;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use JaysonTemporas\TranslationOverrides\TranslationOverridesPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use lockscreen\FilamentLockscreen\Http\Middleware\LockerTimer;
use lockscreen\FilamentLockscreen\Lockscreen;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use Rmsramos\Activitylog\ActivitylogPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use TomatoPHP\FilamentPWA\FilamentPWAPlugin;
use TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin;
use Visualbuilder\EmailTemplates\EmailTemplatesPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            ->login()

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages($this->pages())
            ->widgets($this->widgets())
            ->middleware($this->middleware())
            ->authMiddleware($this->authMiddleware())
            ->plugins($this->plugins())
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop();
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
            SetTheme::class,
            LockerTimer::class,
        ];
    }

    protected function authMiddleware(): array
    {
        return [
            Authenticate::class,
            Locker::class,
        ];
    }

    protected function pages(): array
    {
        return [
            Pages\Dashboard::class,
            // \App\Filament\Pages\TasksBoardBoardPage::class,
            //
        ];
    }

    protected function widgets(): array
    {
        return [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
            OverlookWidget::class,
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
            // FilamentProgressbarPlugin::make(),

            FilamentPurlPlugin::make(),
            FilamentWebpushPlugin::make()
                ->registerSubscriptionStatsWidget(true),
            // FilamentLogViewerPlugin::make(),
            // TranslationOverridesPlugin::make(),

            BreezyCore::make()
                ->myProfile(
                    shouldRegisterUserMenu: true,
                    hasAvatars: true
                )
                ->enableTwoFactorAuthentication(),

            ResizedColumnPlugin::make()
                ->preserveOnDB(),
            FilamentPWAPlugin::make(),
            LightSwitchPlugin::make()
                ->position(Alignment::TopRight),

            ThemesPlugin::make(),
            FilamentSettingsHubPlugin::make()
                ->allowShield()
                ->allowSiteSettings()
                ->allowSocialMenuSettings()
                ->allowLocationSettings(),

            FilamentShieldPlugin::make(),
            ActivitylogPlugin::make(),
            GlobalSearchModalPlugin::make(),
            EnvironmentIndicatorPlugin::make()
                ->visible(fn () => auth()->user()?->hasrole(\App\Enums\RoleEnum::Dev->value)),

            OverlookPlugin::make()
                ->alphabetical(),
            FilamentJobsMonitorPlugin::make(),
            EmailTemplatesPlugin::make(),

            Lockscreen::make(),
        ];
    }
}
