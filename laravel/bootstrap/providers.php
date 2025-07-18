<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // <php-saas:projects>
    App\Providers\ProjectServiceProvider::class,
    // </php-saas:projects>
    // <php-saas:billing>
    App\Providers\BillingServiceProvider::class,
    // </php-saas:billing>
];
