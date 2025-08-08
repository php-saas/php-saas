<?php

namespace App\Providers;

use App\Models\Subscription;
use Illuminate\Support\ServiceProvider;
use Laravel\Paddle\Cashier;

class BillingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);
    }
}
