<?php

return [
    'trial_days' => 14, // set to 0 to disable trials
    'currency' => 'USD',
    'currency_sign' => '$',

    'plans' => [
        // Free plan
        [
            'name' => 'Free',
            'description' => 'Free plan with limited features',
            'price' => 0,
            'features' => [
                '1 Project',
                'API Access',
                'Community Support',
            ],
            'options' => [
                'projects' => true,
                'projects_count' => 1,
                'api' => true,
            ],
            'archived' => false,
        ],

        // Basic plan
        [
            'name' => 'Basic',
            'description' => 'Basic plan with essential features',
            'billing' => 'monthly',
            'price_id' => env('PLAN_BASIC_MONTHLY_ID', ''),
            'price' => 4.99,
            'motivation_text' => null,
            'features' => [
                '5 Projects',
                'API Access',
                'Projects',
                'Email Support',
            ],
            'options' => [
                'projects' => true,
                'projects_count' => 5,
                'api' => true,
            ],
            'archived' => false,
        ],
        [
            'name' => 'Basic',
            'description' => 'Basic plan with essential features',
            'billing' => 'yearly',
            'price_id' => env('PLAN_BASIC_YEARLY_ID', ''),
            'price' => 49.99,
            'motivation_text' => 'Save 20%',
            'features' => [
                '5 Projects',
                'API Access',
                'Projects',
                'Email Support',
            ],
            'options' => [
                'projects' => true,
                'projects_count' => 5,
                'api' => true,
            ],
            'archived' => false,
        ],

        // Pro plan
        [
            'name' => 'Pro',
            'description' => 'Pro plan with advanced features',
            'billing' => 'monthly',
            'price_id' => env('PLAN_PRO_MONTHLY_ID', ''),
            'price' => 9.99,
            'motivation_text' => null,
            'features' => [
                'Unlimited Projects',
                'API Access',
                'Priority Support',
            ],
            'options' => [
                'projects' => true,
                'projects_count' => -1, // -1 means unlimited
                'api' => true,
            ],
            'archived' => false,
        ],
        [
            'name' => 'Pro',
            'description' => 'Pro plan with advanced features',
            'billing' => 'yearly',
            'price_id' => env('PLAN_PRO_YEARLY_ID', ''),
            'price' => 99.99,
            'motivation_text' => 'Save 20%',
            'features' => [
                'Unlimited Projects',
                'API Access',
                'Priority Support',
            ],
            'options' => [
                'projects' => true,
                'projects_count' => -1, // -1 means unlimited
                'api' => true,
            ],
            'archived' => false,
        ],
    ],
];
