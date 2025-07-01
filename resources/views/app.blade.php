<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <x-head />
        <title inertia>TheSaaSKit</title>
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
    <body
        class="bg-background dark:selection:bg-primary/80 selection:bg-primary/80 font-sans antialiased selection:text-white dark:selection:text-white"
    >
        @inertia
    </body>
</html>
