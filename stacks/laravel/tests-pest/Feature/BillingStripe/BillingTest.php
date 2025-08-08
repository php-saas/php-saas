<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Paddle\Subscription;

uses(RefreshDatabase::class);

test('user can see billing page', function () {
    $this->user = prepareStripeUser();
    $this->actingAs($this->user);

    $this->get(route('billing.index'))
        ->assertOk()
        ->assertViewIs('billing.index');
});

test('cannot cancel subscription if already cancelled', function () {
    $this->user = prepareStripeUser();
    $this->actingAs($this->user);

    $this->user->subscription()->update(['ends_at' => now()]);

    $this->delete(route('billing.destroy'))
        ->assertNotFound();
});

test('cannot swap if no subscription exists', function () {
    $this->user = prepareStripeUser();
    $this->actingAs($this->user);

    $this->user->subscriptions()->delete();

    $this
        ->from(route('billing.index'))
        ->post(route('billing.swap'), [
            'price_id' => 'price_654321',
        ])
        ->assertSessionHas(['error' => __('You don\'t have an active subscription.')]);
});

function prepareStripeUser(): User
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

    /** @var Subscription $subscription */
    $subscription = $user->subscriptions()->create([
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

    return $user;
}
