<?php

namespace PHPSaaS\PHPSaaS\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait Billing
{
    /**
     * @throws FileNotFoundException
     */
    protected function setupBilling(): void
    {
        $info = $this->fileSystem->get($this->path.'/info.json');
        $info = json_decode($info, true);

        if ($this->billing === 'paddle') {
            // composer
            $this->runCommands([
                'composer require laravel/cashier-paddle --no-install',
                'composer remove laravel/cashier --no-update',
            ], $this->path);
            $info['billing'] = 'paddle';

            // routes
            $this->fileSystem->move($this->path.'/routes/billing-paddle.php', $this->path.'/routes/billing.php');
            $this->fileSystem->delete($this->path.'/routes/billing-stripe.php');
            $this->fileSystem->delete($this->path.'/routes/billing-paddle.php');

            // service providers
            $this->fileSystem->move($this->path.'/app/Providers/BillingPaddleServiceProvider.php', $this->path.'/app/Providers/BillingServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingStripeServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingPaddleServiceProvider.php');

            // models
            $this->fileSystem->move($this->path.'/app/Models/SubscriptionPaddle.php', $this->path.'/app/Models/Subscription.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionStripe.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionPaddle');

            // migrations
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000001_create_stripe_customers_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2025_07_28_154319_create_stripe_subscriptions_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2025_07_28_154320_create_stripe_subscription_items_table.php');

            // controllers
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/Billing');
            $this->fileSystem->moveDirectory($this->path.'/app/Http/Controllers/BillingPaddle', $this->path.'/app/Http/Controllers/Billing');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingStripe');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingPaddle');

            // views
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing');
            $this->fileSystem->moveDirectory($this->path.'/resources/views/billing-paddle', $this->path.'/resources/views/billing');
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing-stripe');

            // user model
            insert_after_match(
                $this->path.'/app/Models/User.php',
                'use Laravel\\Sanctum\\HasApiTokens;',
                'use Laravel\\Paddle\\Billable;'
            );
            insert_after_match(
                $this->path.'/app/Models/User.php',
                'use TwoFactorAuthenticatable;',
                'use Billable;'
            );
        }

        if ($this->billing === 'stripe') {
            // composer
            $this->runCommands([
                'composer require laravel/cashier --no-install',
                'composer remove laravel/cashier-paddle --no-update',
            ], $this->path);
            $info['billing'] = 'stripe';

            // routes
            $this->fileSystem->move($this->path.'/routes/billing-stripe.php', $this->path.'/routes/billing.php');
            $this->fileSystem->delete($this->path.'/routes/billing-paddle.php');
            $this->fileSystem->delete($this->path.'/routes/billing-stripe.php');

            // service providers
            $this->fileSystem->move($this->path.'/app/Providers/BillingStripeServiceProvider.php', $this->path.'/app/Providers/BillingServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingPaddleServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingStripeServiceProvider.php');

            // models
            $this->fileSystem->move($this->path.'/app/Models/SubscriptionStripe.php', $this->path.'/app/Models/Subscription.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionPaddle.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionStripe.php');

            // migrations
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000001_create_paddle_customers_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000002_create_paddle_subscriptions_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000003_create_paddle_subscription_items_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000004_create_paddle_transactions_table.php');

            // controllers
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/Billing');
            $this->fileSystem->moveDirectory($this->path.'/app/Http/Controllers/BillingStripe', $this->path.'/app/Http/Controllers/Billing');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingPaddle');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingStripe');

            // views
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing');
            $this->fileSystem->moveDirectory($this->path.'/resources/views/billing-stripe', $this->path.'/resources/views/billing');
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing-paddle');

            // user model
            insert_after_match(
                $this->path.'/app/Models/User.php',
                'use Laravel\\Sanctum\\HasApiTokens;',
                'use Laravel\\Cashier\\Billable;'
            );
            insert_after_match(
                $this->path.'/app/Models/User.php',
                'use TwoFactorAuthenticatable;',
                'use Billable;'
            );
        }

        if ($this->billing !== 'none') {
            $webFile = $this->path.'/routes/web.php';
            $webContent = $this->fileSystem->get($webFile);
            if (strpos($webContent, "require __DIR__ . '/billing.php';") === false) {
                $webContent .= "\nrequire __DIR__ . '/billing.php';\n";
                $this->fileSystem->put($webFile, $webContent);
            }
        }

        if ($this->billing === 'none') {
            $info['billing'] = 'none';

            // composer
            $this->runCommands([
                'composer remove laravel/cashier --no-update',
                'composer remove laravel/cashier-paddle --no-update',
            ], $this->path);

            // routes
            $this->fileSystem->delete($this->path.'/routes/billing.php');
            $this->fileSystem->delete($this->path.'/routes/billing-paddle.php');
            $this->fileSystem->delete($this->path.'/routes/billing-stripe.php');

            // service providers
            $this->fileSystem->delete($this->path.'/app/Providers/BillingServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingPaddleServiceProvider.php');
            $this->fileSystem->delete($this->path.'/app/Providers/BillingStripeServiceProvider.php');

            $this->fileSystem->delete($this->path.'/app/Models/Subscription.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionPaddle.php');
            $this->fileSystem->delete($this->path.'/app/Models/SubscriptionStripe.php');

            // controllers
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/Billing');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingPaddle');
            $this->fileSystem->deleteDirectory($this->path.'/app/Http/Controllers/BillingStripe');

            // views
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing');
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing-paddle');
            $this->fileSystem->deleteDirectory($this->path.'/resources/views/billing-stripe');

            // migrations
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000001_create_paddle_customers_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000002_create_paddle_subscriptions_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000003_create_paddle_subscription_items_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000004_create_paddle_transactions_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2019_05_03_000001_create_stripe_customers_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2025_07_28_154319_create_stripe_subscriptions_table.php');
            $this->fileSystem->delete($this->path.'/database/migrations/2025_07_28_154320_create_stripe_subscription_items_table.php');

            // other files
            $this->fileSystem->delete($this->path.'/config/billing.php');
            $this->fileSystem->delete($this->path.'/app/DTOs/BillingPlanDTO.php');
            $this->fileSystem->delete($this->path.'/resources/js/types/plan.d.ts');
            $this->fileSystem->delete($this->path.'/resources/js/types/subscription.d.ts');
            $this->fileSystem->delete($this->path.'/resources/layouts/billing.blade.php');

            // blocks
            $this->removeBlocks($this->path, 'billing');
            $this->removeBlocks($this->path, 'billing-setup');
        }
    }
}
