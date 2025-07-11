@if (request()->query('status') === 'success')
    <x-ui.alert>
        <div class="flex items-center gap-2">
            <x-icons.loading class="animate-spin" />
            {{ __('Please wait...') }}
        </div>
        <script>
            setTimeout(() => {
                window.location.href = '{{ route('billing.index') }}';
            }, 5000);
        </script>
    </x-ui.alert>
@endif

@if ($subscription = user()->subscription())
    <div x-data="{ show: 'current' }">
        @php
            $plan = $subscription->plan();
        @endphp

        <div x-show="show === 'current'" class="space-y-5">
            @if ($plan)
                <x-ui.card class="w-full gap-2!">
                    <x-ui.card.header>
                        <div class="flex items-start justify-between">
                            <div class="grid gap-1.5">
                                <x-ui.card.title>{{ $plan->name }}</x-ui.card.title>
                                <x-ui.card.description>Current Plan</x-ui.card.description>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold">
                                    {{ config('billing.currency_sign') }}{{ $plan->price }}
                                </span>
                                @if ($plan->priceId)
                                    <span class="text-muted-foreground font-semibold">/{{ $plan->billing }}</span>
                                @endif
                            </div>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.content class="space-y-4 px-6">
                        <p class="text-muted-foreground text-sm">{{ $plan->description }}</p>
                        <ul class="space-y-2">
                            @foreach ($plan->features as $feature)
                                <li class="flex items-center gap-2 text-sm">
                                    <svg
                                        class="text-primary size-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="m9 12 2 2 4-4" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card.content>
                </x-ui.card>
            @endif

            <x-ui.card class="w-full">
                <x-ui.card.header>
                    <x-ui.card.title>{{ __('Manage subscription') }}</x-ui.card.title>
                    <x-ui.card.description>
                        {{ __('Here you can manage your subscription') }}
                    </x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content class="space-y-2 px-6">
                    <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center">
                        @if ($subscription->status === 'active' && ! $subscription->ends_at)
                            @include('billing.partials.change-plan')
                            @include('billing.partials.update-payment-method')
                            @include('billing.partials.cancel', ['subscription' => $subscription])
                        @endif
                    </div>

                    @if ($subscription->status === 'active' && $subscription->ends_at)
                        <p class="text-destructive">
                            {{ __('Your subscription will be cancelled at :date', ['date' => $subscription->ends_at->format('Y-m-d')]) }}
                        </p>
                        @include('billing.partials.resume')
                    @endif
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <div x-show="show === 'change'">
            @include('billing.partials.plans', ['subscription' => $subscription])
        </div>
    </div>
@else
    @if (! request()->query('status'))
        <x-ui.alert>
            {{ __("Seems like you've not subscribed to any plans yet. You can see the subscription plains below") }}
        </x-ui.alert>
    @endif
@endif
