<?php

namespace App\Http\Middleware;

use App\Services\Interfaces\UserServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware checks if the user is authenticated
        if (Auth::check() == false) {
            return $next($request);
        }

        $user = Auth::user();
        $userService = app(UserServiceInterface::class);

        // Cache the user's status forever until it's manually cleared
        // Observer will clear this cache when the status is updated
        $isActiveUser = Cache::rememberForever('_user_active_'.$user->id, function () use ($userService, $user) {
            return $userService->checkActive($user->id);
        });

        // If the user is not active, log them out
        if ($isActiveUser == false) {
            Auth::logout();

            // Destroy the session
            $session = $request->session();
            $session->invalidate();
            $session->regenerateToken();
        }

        // Allow the request to proceed
        return $next($request);
    }
}
