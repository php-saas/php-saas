@extends('layouts.billing')

@section('head')
    @paddleJS
@endsection

@section('content')
    <x-ui.container class="max-w-5xl!">
        <x-heading
            title="Billing"
            description="Our billing management system allows you to manage your subscriptions, view invoices, and update payment methods."
        />

        <x-flash />

        @include('billing.partials.subscription')

        @if (! user()->subscription())
            @include('billing.partials.plans')
        @endif

        @include('billing.partials.invoices')
    </x-ui.container>
@endsection
