<?php

namespace App\Http\Controllers;

use App\Actions\User\DeleteUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class ProfileController extends Controller
{
    public function index(): Response
    {
        $user = user();

        return Inertia::render('profile/index', [
            'must_verify_email' => Features::enabled(Features::emailVerification()),
            'two_factor_enabled' => (bool) $user->two_factor_secret,
            'two_factor_recovery_codes' => $user->two_factor_secret ? $user->recoveryCodes() : null,
            'two_factor_confirmed_at' => $user->two_factor_confirmed_at,
            'two_factor_must_confirm' => (bool) config('fortify-options.two-factor-authentication.confirm'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        app(DeleteUser::class)->delete(user(), $request->all());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
