<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteCallbackController extends Controller
{
    public function __invoke(string $provider): RedirectResponse
    {
        $service = config('services.'.$provider);
        if (! $service || ! isset($service['client_id'], $service['client_secret'], $service['redirect'])) {
            abort(404);
        }

        $socialiteUser = Socialite::driver($provider)->user();

        $user = User::query()->where('email', $socialiteUser->getEmail())->first();
        if (! $user) {
            $user = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random()),
            ]);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $user->save();
        }

        if (
            $user->two_factor_secret &&
            config('fortify-options.two-factor-authentication.confirm') &&
            $user->two_factor_confirmed_at
        ) {
            session()->put([
                'login.id' => $user->id,
                'login.remember' => true,
            ]);

            return redirect()->route('two-factor.login');
        }

        auth()->login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
