<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Paddle\Subscription;

/**
 * @mixin Subscription
 */
class SubscriptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var array<string, mixed> $plans */
        $plans = config('billing.plans', []);

        /** @var array<int, string> $prices */
        $prices = $this->items()->pluck('price_id');

        return [
            'id' => $this->id,
            'plan' => collect($plans)->whereIn('price_id', $prices)->first(),
            'status' => $this->status,
            'ends_at' => $this->ends_at,
            'trial_ends_at' => $this->trial_ends_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
