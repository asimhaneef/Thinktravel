<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;

class TwoFactorAuthenticationController extends Controller
{
    public function enable(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());
        
        return response()->json([
            'qr_code' => $request->user()->twoFactorQrCodeSvg(),
            'recovery_codes' => $request->user()->recoveryCodes(),
        ]);
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());
        
        return response()->json(['message' => 'Two-factor authentication disabled.']);
    }

    public function verify(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if (!$request->user()->two_factor_secret ||
            !$provider->verify(
                decrypt($request->user()->two_factor_secret),
                $request->code
            )) {
            return response()->json(['error' => 'Invalid authentication code'], 422);
        }

        return response()->json(['message' => 'Two-factor authentication verified.']);
    }
    public function showChallengeForm()
    {
        return view('auth.two-factor-challenge');
    }
}