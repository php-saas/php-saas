@extends('layouts.home')

@section('content')
    <main>
        <x-ui.container class="min-h-[calc(100vh-249px)] py-10">
            <x-prose>
                <h1>Contact us</h1>
                <p>
                    You can reach us via email for any questions, feedback, or support requests regarding PHP-SaaS.
                    <a href="mailto:contact@php-saas.com">
                        contact
                        @php-saas.dev
                    </a>
                </p>
            </x-prose>
        </x-ui.container>
    </main>
@endsection
