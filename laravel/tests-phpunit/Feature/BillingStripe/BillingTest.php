<?php

namespace Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Paddle\Subscription;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function test_user_can_see_billing_page(): void
    {
        $this->prepare();

        $this->get(route('billing.index'))
            ->assertOk()
            ->assertViewIs('billing.index');
    }

    public function test_cannot_cancel_subscription_if_already_cancelled(): void
    {
        $this->prepare();

        $this->user->subscription()->update(['ends_at' => now()]);

        $this->delete(route('billing.destroy'))
            ->assertNotFound();
    }

    public function test_cannot_swap_if_no_subscription_exists(): void
    {
        $this->prepare();

        $this->user->subscriptions()->delete();

        $this
            ->from(route('billing.index'))
            ->post(route('billing.swap'), [
                'price_id' => 'price_654321',
            ])
            ->assertSessionHas(['error' => __('You don\'t have an active subscription.')]);
    }

    private function prepare(): void
    {
        config()->set('billing.plans', [
            [
                'name' => 'Basic',
                'description' => 'Basic plan',
                'billing' => 'monthly',
                'price_id' => 'price_123456',
                'price' => 10,
                'features' => [],
                'options' => [],
                'archived' => false,
            ],
            [
                'name' => 'Pro',
                'description' => 'Pro plan',
                'billing' => 'monthly',
                'price_id' => 'price_654321',
                'price' => 20,
                'features' => [],
                'options' => [],
                'archived' => false,
            ],
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $this->user = $user;

        $this->actingAs($this->user);

        /** @var Subscription $subscription */
        $subscription = $this->user->subscriptions()->create([
            'type' => 'default',
            'stripe_id' => 'sub_123456',
            'stripe_status' => 'active',
            'stripe_price' => 'price_123456',
            'ends_at' => null,
        ]);
        $subscription->items()->create([
            'stripe_id' => 'si_123456',
            'stripe_product' => 'prod_123456',
            'stripe_price' => 'price_123456',
            'quantity' => 1,
        ]);
    }
}
