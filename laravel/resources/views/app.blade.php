<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <x-head />
        <title inertia>PHP-SaaS</title>
        @viteReactRefresh

        <!--<php-saas:vite>-->
        @php
            $component = $page['component'];
            $vuePath = resource_path("js/pages/{$component}.vue");
            $tsxPath = resource_path("js/pages/{$component}.tsx");
            $pageScript = file_exists($vuePath)
                ? "resources/js/pages/{$component}.vue"
                : "resources/js/pages/{$component}.tsx";
        @endphp

        @vite(['resources/js/app.ts', $pageScript])
        <!--</php-saas:vite>-->

        @inertiaHead
    </head>
    <body
        class="bg-background dark:selection:bg-primary/20 selection:bg-primary/80 font-sans antialiased selection:text-white dark:selection:text-white"
    >
        @inertia
    </body>
</html>
