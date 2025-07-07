<div class="space-y-5 py-6 md:py-10">
    <x-ui.tabs active="monthly">
        <x-ui.tabs.list>
            <x-ui.tabs.trigger name="monthly">Monthly</x-ui.tabs.trigger>
            <x-ui.tabs.trigger name="yearly">Yearly</x-ui.tabs.trigger>
        </x-ui.tabs.list>
        <x-ui.tabs.content name="monthly">
            <div class="flex flex-col items-center md:items-stretch gap-6 md:flex-row justify-center">
                @foreach (config('billing.plans') as $key => $plan)
                    @php($plan = \App\DTOs\BillingPlanDTO::fromArray($plan))
                    @if ($plan->getBilling() === 'monthly' || ! $plan->getPriceId())
                        @include('billing.partials.plan', ['plan' => $plan])
                    @endif
                @endforeach
            </div>
        </x-ui.tabs.content>
        <x-ui.tabs.content name="yearly">
            <div class="flex flex-col items-center md:items-stretch gap-6 md:flex-row justify-center">
                @foreach (config('billing.plans') as $plan)
                    @php($plan = \App\DTOs\BillingPlanDTO::fromArray($plan))
                    @if ($plan->getBilling() === 'yearly' || ! $plan->getPriceId())
                        @include('billing.partials.plan', ['plan' => $plan])
                    @endif
                @endforeach
            </div>
        </x-ui.tabs.content>
    </x-ui.tabs>
</div>
