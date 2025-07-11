<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteRedirectController extends Controller
{
    public function __invoke(string $provider): RedirectResponse
    {
        $service = config('services.'.$provider);
        if (! $service || ! isset($service['client_id'], $service['client_secret'], $service['redirect'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }
}
