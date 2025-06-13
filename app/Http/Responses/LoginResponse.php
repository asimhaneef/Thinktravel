<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();
        
        if ($user->is_first_login) {
            $user->update(['is_first_login' => false]);
            return redirect()->route('password.change');
        }
        
        // Handle 2FA scenario
        if (Features::enabled(Features::twoFactorAuthentication()) && 
            $user->two_factor_enabled) {
            
            return redirect()->route('two-factor-challenge');
        }
        
        return redirect()->intended(config('fortify.home'));
    }
}
?>