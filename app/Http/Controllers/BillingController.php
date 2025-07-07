<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Laravel\Paddle\Subscription;
use Laravel\Paddle\Transaction;

class BillingController
{
    public function index(): View
    {
        return view('billing/index');
    }

    public function updatePaymentMethod(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->subscription()) {
            abort(404);
        }

        return $user->subscription()->redirectToUpdatePaymentMethod();
    }

    public function cancel(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $subscription = $user->subscription();

        if ($subscription && $subscription->ends_at) {
            return back()->with('warning', __('Your subscription ends at :date', ['date' => $subscription->ends_at->format('Y-m-d')]));
        }

        if (!$subscription?->active()) {
            session()->flash('success', __('You don\'t have an active subscription.'));
        }

        $subscription?->cancel();

        session()->flash('success', __('Your subscription has been cancelled successfully.'));

        return redirect()->route('billing.index');
    }

    public function resume(): RedirectResponse
    {
        /** @var ?Subscription $subscription */
        $subscription = user()->subscription();

        if (!$subscription || !$subscription->active() || !$subscription->ends_at) {
            session()->flash('success', 'You don\'t have an active subscription.');
        }

        $subscription?->stopCancelation();

        session()->flash('success', __('Subscription resumed successfully.'));

        // wait for the webhook
        sleep(1);

        return redirect()->route('billing.index');
    }

    public function download(Transaction $transaction): RedirectResponse
    {
        if ($transaction->billable_id !== user()->id) {
            abort(404);
        }

        return $transaction->redirectToInvoicePdf();
    }
}
