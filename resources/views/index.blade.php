@extends('layouts.home')

@section('content')
    <div class="space-y-20 py-10 md:py-16">
        <header>
            <x-ui.container class="md:text-center">
                <h1 class="text-5xl leading-none sm:text-5xl md:text-6xl xl:text-7xl">
                    <span class="text-brand block font-extrabold tracking-tight">{{ config('app.name') }}</span>
                </h1>
                <p class="relative mt-3 inline-block text-2xl">
                    The everything-included starter kit for your next idea
                </p>
                <p class="text-muted-foreground mx-auto text-sm">Powered by Laravel</p>
                <div class="flex items-center justify-center gap-2">
                    <x-ui.button size="lg" as="a" href="{{ route('register') }}">Register</x-ui.button>
                    <x-ui.button variant="outline" size="lg" as="a" href="#" target="_blank">Documentation</x-ui.button>
                </div>
            </x-ui.container>
        </header>

        <section id="features">
            <x-ui.container class="flex flex-col items-start justify-between gap-10 md:flex-row">
                <div class="space-y-4 leading-relaxed tracking-wide md:max-w-3/6">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Features</h2>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                        but also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                        and more recently with desktop publishing software like Aldus PageMaker including versions of
                        Lorem Ipsum.
                    </p>
                </div>
                <div class="bg-muted h-[300px] w-full rounded-xl md:max-w-3/6"></div>
            </x-ui.container>
        </section>

        <!--<php-saas:billing>-->
        <section id="pricing">
            <x-ui.container>
                @include('billing.partials.plans', ['public' => true])
            </x-ui.container>
        </section>
        <!--<php-saas:billing>-->
    </div>
@endsection
