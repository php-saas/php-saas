<!DOCTYPE html>
<html
    x-data
    x-cloak
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark', 'scroll-smooth'])
>
    <head>
        <x-head />
        <title>
            {{ isset($title) && $title ? $title . ' - ' . config('app.name') : 'Billing - ' . config('app.name') }}
        </title>
        @vite(['resources/css/app.css', 'resources/js-home/app.js'])
        @yield('head')
    </head>
    <body
        class="bg-background dark:selection:bg-primary/30 dark:selection:text-foreground selection:bg-primary/10 selection:text-primary min-h-svh font-sans antialiased"
    >
        <div>
            <div x-data="{ menu: false }" class="mx-auto flex h-16 items-center px-4 md:max-w-5xl">
                <a href="/">
                    <x-logo class="mt-1.5 size-10" />
                </a>
                <div class="ml-2 flex h-10 w-full items-start justify-between">
                    <div>
                        <h1 class="font-semibold">{{ __('Billing Management') }}</h1>
                        <p class="text-muted-foreground text-sm">
                            {{ __('Logged in as :user', ['user' => user()->name]) }}
                        </p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 underline">
                        {{ __('Return to dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div>
            @yield('content')
        </div>
    </body>
</html>
