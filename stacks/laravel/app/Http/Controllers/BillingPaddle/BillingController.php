<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BillingController extends Controller
{
    public function index(): View
    {
        return view('billing/index');
    }

    public function destroy(): RedirectResponse
    {
        $user = user();

        $subscription = $user->subscription();

        if (! $subscription || $subscription->ends_at || ! $subscription->active()) {
            abort(404);
        }

        $subscription->cancel();

        return redirect()
            ->route('billing.index')
            ->with('success', __('Your subscription has been cancelled successfully.'));
    }
}
