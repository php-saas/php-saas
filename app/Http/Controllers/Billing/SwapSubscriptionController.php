<?php

namespace App\Http\Controllers\Billing;

use App\DTOs\BillingPlanDTO;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwapSubscriptionController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $subscription = user()->subscription();

        if (! $subscription || ! $subscription->active() || $subscription->ends_at) {
            return back()->with('error', 'You don\'t have an active subscription.');
        }

        $plans = config('billing.plans');

        $newPlan = collect($plans)
            ->where('price_id', $request->input('price_id'))
            ->where('archived', false)
            ->first();
        if (! $newPlan) {
            return back()->with('error', __('Invalid plan selected.'));
        }
        $newPlan = BillingPlanDTO::from($newPlan);

        // Downgrades or Yearly → Monthly → schedule or just swap without invoice
        $subscription->swapAndInvoice($newPlan->priceId);

        return back()->with('success', __('Your subscription has been successfully swapped to :plan.', [
            'plan' => $newPlan->name,
        ]));

    }
}
