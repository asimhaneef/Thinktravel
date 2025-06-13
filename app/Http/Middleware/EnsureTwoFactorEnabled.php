<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (Features::enabled(Features::twoFactorAuthentication()) &&
            $user->two_factor_enabled &&
            !$request->is('two-factor*') &&
            !$request->is('logout')) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Two-factor authentication required.'], 403)
                : redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}