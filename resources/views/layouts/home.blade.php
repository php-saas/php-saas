<!DOCTYPE html>
<html
    x-data
    x-cloak
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark', 'scroll-smooth'])
>
    <head>
        <x-head />
        <title>{{ isset($title) && $title ? $title . ' - TheSaaSKit' : 'TheSaaSKit' }}</title>
        @vite(['resources/css/app.css', 'resources/js/home.js'])
    </head>
    <body
        class="bg-background dark:selection:bg-primary/30 dark:selection:text-foreground selection:bg-primary/10 selection:text-primary min-h-svh font-sans antialiased"
    >
        <x-navbar />
        <div class="py-16 md:py-20">
            @yield('content')
        </div>
        <x-footer />
    </body>
</html>
