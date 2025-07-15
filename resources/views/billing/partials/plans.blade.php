<div class="space-y-5 py-6 md:py-10">
    <x-ui.tabs active="monthly">
        <x-ui.tabs.list class="mb-4">
            <x-ui.tabs.trigger name="monthly">Monthly</x-ui.tabs.trigger>
            <x-ui.tabs.trigger name="yearly">Yearly</x-ui.tabs.trigger>
        </x-ui.tabs.list>
        <x-ui.tabs.content name="monthly">
            <div class="flex flex-col items-center justify-center gap-6 md:flex-row md:items-stretch">
                @foreach (config('billing.plans') as $key => $plan)
                    @php($plan = \App\DTOs\BillingPlanDTO::from($plan))
                    @if ($plan->billing === 'monthly' || $plan->price == 0)
                        @include('billing.partials.plan', ['plan' => $plan, 'subscription' => $subscription ?? null])
                    @endif
                @endforeach
            </div>
        </x-ui.tabs.content>
        <x-ui.tabs.content name="yearly">
            <div class="flex flex-col items-center justify-center gap-6 md:flex-row md:items-stretch">
                @foreach (config('billing.plans') as $plan)
                    @php($plan = \App\DTOs\BillingPlanDTO::from($plan))
                    @if ($plan->billing === 'yearly' || $plan->price == 0)
                        @include('billing.partials.plan', ['plan' => $plan, 'subscription' => $subscription ?? null])
                    @endif
                @endforeach
            </div>
        </x-ui.tabs.content>
    </x-ui.tabs>
</div>
