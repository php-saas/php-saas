<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Subscription;
use Laravel\Paddle\SubscriptionItem;
use Laravel\Paddle\Transaction;

uses(RefreshDatabase::class);

test('user can see billing page', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->get(route('billing.index'))
        ->assertOk()
        ->assertViewIs('billing.index');
});

test('user can cancel subscription', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    Cashier::fake()->response('subscriptions/sub_123456/cancel', json_decode(file_get_contents(base_path('tests/Feature/Billing/response.json')), true));

    expect($this->user->fresh()->subscription()->ends_at)->toBeNull();

    $this->delete(route('billing.destroy'))
        ->assertRedirect(route('billing.index'));

    expect($this->user->fresh()->subscription()->ends_at)->not->toBeNull();
});

test('cannot cancel subscription if already cancelled', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->user->subscription()->update(['ends_at' => now()]);

    $this->delete(route('billing.destroy'))
        ->assertNotFound();
});

test('user can download invoice', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $transaction = Transaction::query()->create([
        'paddle_id' => 'txn_123456',
        'billable_id' => $this->user->id,
        'billable_type' => User::class,
        'invoice_number' => 'inv_123456',
        'total' => 1000,
        'tax' => 0,
        'currency' => 'USD',
        'status' => 'paid',
        'created_at' => now(),
        'billed_at' => now(),
    ]);

    Cashier::fake()->response('transactions/txn_123456/invoice', [
        'url' => 'https://example.com/invoice.pdf',
    ]);

    $response = $this->get(route('billing.invoices.download', ['transaction' => $transaction->id]));

    $response->assertRedirect('https://example.com/invoice.pdf');
});

test('cannot download others invoice', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $transaction = Transaction::query()->create([
        'paddle_id' => 'txn_123456',
        'billable_id' => 1234,
        'billable_type' => User::class,
        'invoice_number' => 'inv_123456',
        'total' => 1000,
        'tax' => 0,
        'currency' => 'USD',
        'status' => 'paid',
        'created_at' => now(),
        'billed_at' => now(),
    ]);

    $response = $this->get(route('billing.invoices.download', ['transaction' => $transaction->id]));

    $response->assertNotFound();
});

test('user can resume a cancelled subscription', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->user->subscription()->update(['ends_at' => now()]);

    Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/response.json')), true));

    $this->post(route('billing.resume'))
        ->assertRedirect(route('billing.index'));

    expect($this->user->fresh()->subscription()->ends_at)->toBeNull();
});

test('cannot resume if no subscription exists', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->user->subscriptions()->delete();

    $this->post(route('billing.resume'))
        ->assertNotFound();
});

test('user can change subscription plan', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/swap-response.json')), true));

    $this
        ->from(route('billing.index'))
        ->post(route('billing.swap'), [
            'price_id' => 'pri_654321',
        ])
        ->assertSessionHas('success');

    /** @var ?SubscriptionItem $item */
    $item = $this->user->subscription()->items()->first();

    expect($item?->price_id)->toEqual('pri_654321');
});

test('swap fails plan not exist', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/swap-response.json')), true));

    config()->set('billing.plans', []);

    $this
        ->from(route('billing.index'))
        ->post(route('billing.swap'), [
            'price_id' => 'pri_non_existent',
        ])
        ->assertSessionHas(['error' => __('Invalid plan selected.')]);
});

test('cannot swap if no subscription exists', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->user->subscriptions()->delete();

    $this
        ->from(route('billing.index'))
        ->post(route('billing.swap'), [
            'price_id' => 'pri_654321',
        ])
        ->assertSessionHas(['error' => __('You don\'t have an active subscription.')]);
});

test('user can change payment method', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    Cashier::fake()->response('subscriptions/sub_123456', [
        'management_urls' => [
            'update_payment_method' => 'https://paddle.com',
        ],
    ]);

    $this->get(route('billing.update-payment-method'))
        ->assertRedirect('https://paddle.com');
});

test('cannot update payment method if no subscription exists', function () {
    $this->user = preparePaddleUser();
    $this->actingAs($this->user);

    $this->user->subscriptions()->delete();

    $this->get(route('billing.update-payment-method'))
        ->assertNotFound();
});

function preparePaddleUser(): User
{
    config()->set('billing.plans', [
        [
            'name' => 'Basic',
            'description' => 'Basic plan',
            'billing' => 'monthly',
            'price_id' => 'pri_123456',
            'price' => 10,
            'features' => [],
            'options' => [],
            'archived' => false,
        ],
        [
            'name' => 'Pro',
            'description' => 'Pro plan',
            'billing' => 'monthly',
            'price_id' => 'pri_654321',
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
        'paddle_id' => 'sub_123456',
        'status' => 'active',
        'ends_at' => null,
    ]);
    $subscription->items()->create([
        'product_id' => 'prod_123456',
        'price_id' => 'pri_123456',
        'quantity' => 1,
        'status' => 'active',
    ]);

    return $user;
}
