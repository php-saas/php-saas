<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\RedirectResponse;

class UpdatePaymentMethodController
{
    public function __invoke(): RedirectResponse
    {
        $user = user();

        if (! $user->subscription()) {
            abort(404);
        }

        return $user->subscription()->redirectToUpdatePaymentMethod();
    }
}
