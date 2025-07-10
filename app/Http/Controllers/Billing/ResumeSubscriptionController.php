<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\RedirectResponse;
use Laravel\Paddle\Subscription;

class ResumeSubscriptionController
{
    public function __invoke(): RedirectResponse
    {
        /** @var ?Subscription $subscription */
        $subscription = user()->subscription();

        if (! $subscription || ! $subscription->active()) {
            abort(404);
        }

        $subscription->stopCancelation();

        return redirect()
            ->route('billing.index')
            ->with('success', __('Your subscription has been resumed successfully.'));
    }
}
