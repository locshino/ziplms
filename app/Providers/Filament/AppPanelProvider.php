<?php

namespace App\Providers\Filament;

use App\Filament\Pages;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RenewPassword;
use App\Http\Middleware\CheckUserActive;
use App\Models\User;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Cmsmaxinc\FilamentErrorPages\FilamentErrorPagesPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filafly\Themes\Brisk\BriskTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use lockscreen\FilamentLockscreen\Lockscreen;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Tapp\FilamentMailLog\FilamentMailLogPlugin;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use ZPMLabs\FilamentApiDocsBuilder\FilamentApiDocsBuilderPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')

            ->login(Login::class)
            ->passwordReset()
            ->profile(page: EditProfile::class, isSimple: false)

            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                //
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,

                StartSession::class,
                CheckUserActive::class,
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
            ->plugins($this->setPlugins())
            ->viteTheme('resources/css/filament/app/theme.css')
            // ->strictAuthorization()
            ->sidebarCollapsibleOnDesktop()
            ->spa(hasPrefetching: true)
            ->databaseNotifications()
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => view('filament.components.quiz-in-progress-global-alert')->render()
            );
    }

    /**
     * Sets up and returns the array of plugins for the panel.
     * This method is refactored for conciseness and maintainability.
     */
    public function setPlugins(): array
    {
        $config = config('ziplms.plugins');
        $isAppEnvLocal = app()->environment('local');

        $plugins = [
            // Authentication
            FilamentShieldPlugin::make(),
            Lockscreen::make()
                // ->usingCustomTableColumns() // Use custom table columns. Default:  email, password.
                ->enableRateLimit() // Enable rate limit for the lockscreen. Default: Enable, 5 attempts in 1 minute.
                // ->setUrl() // Customize the lockscreen url.
                ->enableIdleTimeout() // Enable auto lock during idle time. Default: Enable, 30 minutes.
                // ->disableDisplayName() // Display the name of the user based on the attribute supplied. Default: name
                // ->icon() // Customize the icon of the lockscreen.
                ->enablePlugin(), // Enable the plugin.

            TwoFactorAuthenticationPlugin::make()
                ->enableTwoFactorAuthentication() // Enable Google 2FA
                ->enablePasskeyAuthentication() // Enable Passkey
                ->addTwoFactorMenuItem(), // Add 2FA menu item

            // Logs
            FilamentAuthenticationLogPlugin::make(),
            FilamentMailLogPlugin::make(),
            FilamentLogViewerPlugin::make()
                ->listLogs(Pages\Logs\ListLogs::class)
                ->viewLog(Pages\Logs\ViewLog::class),

            // UI
            FilamentApexChartsPlugin::make(),
            FilamentErrorPagesPlugin::make(),
            SpotlightPlugin::make(),
            BriskTheme::make()->withoutSuggestedFont(),
            ResizedColumnPlugin::make()
                ->preserveOnDB(),

            // Pages
            FilamentApiDocsBuilderPlugin::make(),

            // Dev Mode
            FilamentDeveloperLoginsPlugin::make()
                ->switchable($isAppEnvLocal)
                ->enabled($isAppEnvLocal)
                ->users([
                    'Super Administrator' => 'superadmin@example.com',
                    'Administrator' => 'admin@example.com',
                    'Manager' => 'manager@example.com',
                    'Teacher' => 'teacher@example.com',
                    'Student' => 'student@example.com',
                ]),
        ];

        // Authentication
        if ($config['renew_password']['enabled']) {
            $plugins[] = RenewPasswordPlugin::make()
                ->passwordExpiresIn(days: $config['renew_password']['password_expires_in'])
                ->renewPage(RenewPassword::class)
                ->forceRenewPassword($config['renew_password']['force_renew_password']);
        }

        // UI
        if ($config['backgrounds']['enabled']) {
            $plugins[] = FilamentBackgroundsPlugin::make()
                ->remember($config['backgrounds']['remember_in_seconds'])
                ->imageProvider(
                    MyImages::make()
                        ->directory('images/backgrounds')
                );
        }

        // UI
        if ($config['easy_footer']['enabled']) {
            $plugins[] = EasyFooterPlugin::make();
        }

        // Authentication
        if ($config['socialite']['enabled']) {
            $plugins[] = FilamentSocialitePlugin::make()
                ->registration($config['socialite']['allow_registration'] == true)
                ->providers([
                    Provider::make('google')
                        ->label('Google')
                        ->icon('fab-google'),
                ]);
        }

        // UI
        if ($config['environment_indicator']['enabled']) {
            $plugins[] = EnvironmentIndicatorPlugin::make()
                ->showBadge(true)
                ->showBorder(false)
                ->showDebugModeWarningInProduction();
        }

        return $plugins;
    }
}
