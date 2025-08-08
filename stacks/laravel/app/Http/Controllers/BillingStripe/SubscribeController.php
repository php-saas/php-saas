<?php

namespace App\Http\Controllers\Billing;

use App\DTOs\BillingPlanDTO;
use App\Http\Controllers\Controller;
use Laravel\Cashier\Checkout;

class SubscribeController extends Controller
{
    public function __invoke(string $plan, string $billing): Checkout
    {
        $user = user();

        $plan = collect(config('billing.plans'))
            ->where('name', $plan)
            ->where('billing', $billing)
            ->where('archived', false)
            ->first();

        if (! $plan) {
            abort(404);
        }

        $plan = BillingPlanDTO::from($plan);

        return $user->newSubscription('default', $plan->priceId)->checkout([
            'success_url' => route('billing.index', ['status' => 'success']),
            'cancel_url' => route('billing.index', ['status' => 'cancelled']),
        ]);
    }
}
