@extends('layouts.home')

@section('content')
    <div class="space-y-20 py-16 md:py-20">
        <header>
            <x-ui.container class="md:text-center">
                <h1 class="text-5xl leading-none font-extrabold sm:text-5xl md:text-6xl xl:text-7xl">
                    <span class="text-brand block tracking-tight">Start Kit</span>
                    <span class="relative mt-3 inline-block text-3xl">
                        The only start kit you need to start your next idea
                    </span>
                </h1>
                <p
                    class="text-muted-foreground mx-auto text-left text-sm sm:text-base md:max-w-xl md:text-center md:text-lg xl:text-xl"
                >
                    Powered by Laravel
                </p>
                <div class="flex items-center justify-center gap-2">
                    <x-ui.button size="lg" as="a" href="{{ route('register') }}">Pricing</x-ui.button>
                    <x-ui.button variant="outline" size="lg" as="a" href="#" target="_blank">Documentation</x-ui.button>
                </div>
            </x-ui.container>
        </header>
    </div>
@endsection
