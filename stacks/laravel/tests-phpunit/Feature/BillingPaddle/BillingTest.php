<?php

namespace Tests\Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Subscription;
use Laravel\Paddle\SubscriptionItem;
use Laravel\Paddle\Transaction;
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

    public function test_user_can_cancel_subscription(): void
    {
        $this->prepare();

        Cashier::fake()->response('subscriptions/sub_123456/cancel', json_decode(file_get_contents(base_path('tests/Feature/Billing/response.json')), true));

        $this->assertNull($this->user->fresh()->subscription()->ends_at);

        $this->delete(route('billing.destroy'))
            ->assertRedirect(route('billing.index'));

        $this->assertNotNull($this->user->fresh()->subscription()->ends_at);
    }

    public function test_cannot_cancel_subscription_if_already_cancelled(): void
    {
        $this->prepare();

        $this->user->subscription()->update(['ends_at' => now()]);

        $this->delete(route('billing.destroy'))
            ->assertNotFound();
    }

    public function test_user_can_download_invoice(): void
    {
        $this->prepare();

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
    }

    public function test_cannot_download_others_invoice(): void
    {
        $this->prepare();

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
    }

    public function test_user_can_resume_a_cancelled_subscription(): void
    {
        $this->prepare();

        $this->user->subscription()->update(['ends_at' => now()]);

        Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/response.json')), true));

        $this->post(route('billing.resume'))
            ->assertRedirect(route('billing.index'));

        $this->assertNull($this->user->fresh()->subscription()->ends_at);
    }

    public function test_cannot_resume_if_no_subscription_exists(): void
    {
        $this->prepare();

        $this->user->subscriptions()->delete();

        $this->post(route('billing.resume'))
            ->assertNotFound();
    }

    public function test_user_can_change_subscription_plan(): void
    {
        $this->prepare();

        Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/swap-response.json')), true));

        $this
            ->from(route('billing.index'))
            ->post(route('billing.swap'), [
                'price_id' => 'pri_654321',
            ])
            ->assertSessionHas('success');

        /** @var ?SubscriptionItem $item */
        $item = $this->user->subscription()->items()->first();

        $this->assertEquals('pri_654321', $item?->price_id);
    }

    public function test_swap_fails_plan_not_exist(): void
    {
        $this->prepare();

        Cashier::fake()->response('subscriptions/sub_123456', json_decode(file_get_contents(base_path('tests/Feature/Billing/swap-response.json')), true));

        config()->set('billing.plans', []);

        $this
            ->from(route('billing.index'))
            ->post(route('billing.swap'), [
                'price_id' => 'pri_non_existent',
            ])
            ->assertSessionHas(['error' => __('Invalid plan selected.')]);
    }

    public function test_cannot_swap_if_no_subscription_exists(): void
    {
        $this->prepare();

        $this->user->subscriptions()->delete();

        $this
            ->from(route('billing.index'))
            ->post(route('billing.swap'), [
                'price_id' => 'pri_654321',
            ])
            ->assertSessionHas(['error' => __('You don\'t have an active subscription.')]);
    }

    public function test_user_can_change_payment_method(): void
    {
        $this->prepare();

        Cashier::fake()->response('subscriptions/sub_123456', [
            'management_urls' => [
                'update_payment_method' => 'https://paddle.com',
            ],
        ]);

        $this->get(route('billing.update-payment-method'))
            ->assertRedirect('https://paddle.com');
    }

    public function test_cannot_update_payment_method_if_no_subscription_exists(): void
    {
        $this->prepare();

        $this->user->subscriptions()->delete();

        $this->get(route('billing.update-payment-method'))
            ->assertNotFound();
    }

    private function prepare(): void
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
        $this->user = $user;

        $this->actingAs($this->user);

        /** @var Subscription $subscription */
        $subscription = $this->user->subscriptions()->create([
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
    }
}
