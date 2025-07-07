<?php

return [
    'trial_days' => 14, // set to 0 to disable trials

    'plans' => [
        // Free plan
        \App\DTOs\BillingPlanDTO::make()
            ->name('Free')
            ->description('Free plan with limited features')
            ->billing('monthly')
            ->features([
                '1 Project',
                'API Access',
                'Community Support',
            ])
            ->options([
                'projects' => true,
                'projects_count' => 1,
                'api' => true,
            ])
            ->toArray(),

        // Basic plan
        \App\DTOs\BillingPlanDTO::make()
            ->name('Basic')
            ->description('Basic plan with essential features')
            ->billing('monthly')
            ->price(4.99)
            ->priceId(env('PLAN_BASIC_MONTHLY_ID', ''))
            ->features([
                '5 Projects',
                'API Access',
                'Projects',
                'Email Support'
            ])
            ->options([
                'projects' => true,
                'projects_count' => 5,
                'api' => true,
            ])
            ->toArray(),
        \App\DTOs\BillingPlanDTO::make()
            ->name('Basic')
            ->description('Basic plan with essential features')
            ->billing('yearly')
            ->price(49.99)
            ->priceId(env('PLAN_BASIC_YEARLY_ID', ''))
            ->motivationText('Save 20%')
            ->features([
                '5 Projects',
                'API Access',
                'Projects',
                'Email Support'
            ])
            ->options([
                'projects' => true,
                'projects_count' => 5,
                'api' => true,
            ])
            ->toArray(),

        // Pro plan
        \App\DTOs\BillingPlanDTO::make()
            ->name('Pro')
            ->description('Pro plan with advanced features')
            ->billing('monthly')
            ->price(9.99)
            ->priceId(env('PLAN_PRO_MONTHLY_ID', ''))
            ->features([
                'Unlimited Projects',
                'API Access',
                'Priority Support',
            ])
            ->options([
                'projects' => true,
                'projects_count' => -1, // -1 means unlimited
                'api' => true,
            ])
            ->toArray(),
        \App\DTOs\BillingPlanDTO::make()
            ->name('Pro')
            ->description('Pro plan with advanced features')
            ->billing('yearly')
            ->price(99.99)
            ->priceId(env('PLAN_PRO_YEARLY_ID', ''))
            ->motivationText('Save 20%')
            ->features([
                'Unlimited Projects',
                'API Access',
                'Priority Support',
            ])
            ->options([
                'projects' => true,
                'projects_count' => -1, // -1 means unlimited
                'api' => true,
            ])
            ->toArray(),
    ]
];
