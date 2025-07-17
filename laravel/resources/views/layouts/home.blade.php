<!DOCTYPE html>
<html
    x-data
    x-cloak
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark', 'scroll-smooth'])
>
    <head>
        <x-head />
        <title>{{ isset($title) && $title ? $title . ' - PHP-SaaS' : 'PHP-SaaS' }}</title>
        @vite(['resources/css/app.css', 'resources/js-home/app.js'])
    </head>
    <body
        class="bg-background dark:selection:bg-primary/20 selection:bg-primary/80 min-h-svh font-sans antialiased selection:text-white dark:selection:text-white"
    >
        <x-navbar />
        <div class="py-16 md:py-20">
            @yield('content')
        </div>
        <x-footer />
    </body>
</html>
