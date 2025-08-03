<?php

namespace App\Providers;

use App\Models\Subscription;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier as CashierStripe;
use Laravel\Cashier\CashierServiceProvider as CashierStripeServiceProvider;
use Laravel\Paddle\Cashier as CashierPaddle;
use Laravel\Paddle\CashierServiceProvider as CashierPaddleServiceProvider;

class BillingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (config('php-saas.billing') === 'paddle') {
            $this->app->register(CashierPaddleServiceProvider::class);
        }

        if (config('php-saas.billing') === 'stripe') {
            $this->app->register(CashierStripeServiceProvider::class);

        }
    }

    public function boot(): void
    {
        if (config('php-saas.billing') === 'paddle') {
            CashierPaddle::useSubscriptionModel(Subscription::class);
        }

        if (config('php-saas.billing') === 'stripe') {
            CashierStripe::useSubscriptionModel(Subscription::class);
        }
    }
}
