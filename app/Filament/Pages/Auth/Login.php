<?php

namespace App\Filament\Pages\Auth;

use App\Models\Authenticatable;
use App\Services\Interfaces\UserServiceInterface;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\MultiFactor\Contracts\HasBeforeChallengeHook;
use Filament\Auth\Pages\Login as FilamentLogin;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;

class Login extends FilamentLogin
{
    // protected string $view = 'filament.pages.auth.login';

    public function authenticate(): ?LoginResponse
    {
        // Step 1: Rate limit login attempts to prevent brute force
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            // If too many requests, send notification and stop authentication
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        // Step 2: Get form data (credentials, remember, etc.)
        $data = $this->form->getState();

        // Step 3: Get the authentication guard and provider
        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();
        $authProvider = $authGuard->getProvider();

        // Step 4: Build credentials array from form data
        /** @phpstan-ignore-line */
        $credentials = $this->getCredentialsFromFormData($data);

        // Step 5: Try to retrieve user by credentials
        $user = $authProvider->retrieveByCredentials($credentials);

        // Step 6: Check user account status
        $userService = $this->getUserService();

        $isNotExistOrActiveUser = !$user || !$userService->checkActive($user?->id);

        if ($isNotExistOrActiveUser || (! $authProvider->validateCredentials($user, $credentials))) {
            /** @var \Illuminate\Contracts\Auth\Guard $authGuard */
            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        // Step 7: Handle multi-factor authentication (MFA)
        if (
            filled($this->userUndertakingMultiFactorAuthentication) &&
            (decrypt($this->userUndertakingMultiFactorAuthentication) === $user->getAuthIdentifier())
        ) {
            // If MFA challenge is already underway, validate the challenge form
            $this->multiFactorChallengeForm->validate();
        } else {
            // Otherwise, check if any MFA provider is enabled for this user
            foreach (Filament::getMultiFactorAuthenticationProviders() as $multiFactorAuthenticationProvider) {
                if (! $multiFactorAuthenticationProvider->isEnabled($user)) {
                    continue;
                }
                // Set MFA state and run beforeChallenge hook if available
                $this->userUndertakingMultiFactorAuthentication = encrypt($user->getAuthIdentifier());
                if ($multiFactorAuthenticationProvider instanceof HasBeforeChallengeHook) {
                    $multiFactorAuthenticationProvider->beforeChallenge($user);
                }
                break;
            }
            // If MFA is required, fill challenge form and stop authentication until challenge is complete
            if (filled($this->userUndertakingMultiFactorAuthentication)) {
                $this->multiFactorChallengeForm->fill();
                return null;
            }
        }

        // Step 8: Attempt to log in the user, checking panel access if needed
        if (! $authGuard->attemptWhen($credentials, function (Authenticatable $user): bool {
            if (! ($user instanceof FilamentUser)) {
                return true;
            }
            // Only allow login if user can access the current panel
            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $data['remember'] ?? false)) {
            /** @var \Illuminate\Contracts\Auth\Guard $authGuard */
            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        // Step 9: Regenerate session to prevent session fixation
        session()->regenerate();

        // Step 10: Return login response (success)
        return app(LoginResponse::class);
    }

    /**
     * Get the user service instance.
     */
    private function getUserService(): UserServiceInterface
    {
        return app(UserServiceInterface::class);
    }
}
