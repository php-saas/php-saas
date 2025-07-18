<?php

namespace App\Models;

use App\DTOs\BillingPlanDTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Paddle\Subscription as CashierSubscription;
use Laravel\Paddle\SubscriptionItem;

/**
 * @property int $id
 * @property int $billable_id
 * @property string $billable_type
 * @property string $type
 * @property string $paddle_id
 * @property string $status
 * @property ?Carbon $trial_ends_at
 * @property ?Carbon $paused_at
 * @property ?Carbon $ends_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subscription extends CashierSubscription
{
    use HasFactory;

    public function plan(): BillingPlanDTO
    {
        /** @var SubscriptionItem $item */
        $item = $this->items()->first();

        $planArray = collect(config('billing.plans'))
            ->where('price_id', $item->price_id)
            ->first();

        return BillingPlanDTO::from($planArray);
    }
}
