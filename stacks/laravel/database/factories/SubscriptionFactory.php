<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'billable_id' => 1,
            'billable_type' => 'App\Models\User',
            'type' => 'default',
            'paddle_id' => $this->faker->uuid,
            'status' => 'active',
        ];
    }
}
