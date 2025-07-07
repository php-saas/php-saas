@php
    use App\DTOs\BillingPlanDTO;
@endphp

@php
    /** @var BillingPlanDTO $plan */
@endphp

<x-ui.card class="w-full">
    <x-ui.card.header>
        <div class="flex h-6 items-center gap-2 leading-none font-semibold">
            <p>{{ $plan->getName() }}</p>
            @if ($plan->getMotivationText())
                <span class="text-muted-foreground bg-muted rounded-md px-2 py-1 text-xs">
                    {{ $plan->getMotivationText() }}
                </span>
            @endif
        </div>
        <p class="text-muted-foreground text-sm">{{ $plan->getDescription() }}</p>
        <div class="flex items-end">
            <span class="text-4xl font-semibold">${{ $plan->getPrice() }}</span>
            @if ($plan->getPriceId())
                <span class="text-muted-foreground text-2xl font-semibold">/{{ $plan->getBilling() }}</span>
            @endif
        </div>
    </x-ui.card.header>
    <x-ui.card-content>
        <x-ui.separator class="mb-6" />
        <ul class="space-y-4 px-6">
            @foreach ($plan->getFeatures() as $feature)
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
    </x-ui.card-content>
    <x-ui.card.footer>
        @if ($plan->getPriceId())
            <x-ui.button
                x-data="{processing: false}"
                x-on:click="processing = true; setTimeout(() => { processing = false }, 1000)"
                as="div"
                class="w-full p-0!"
            >
                <x-paddle-button
                    :checkout="user()->subscribe($plan->getPriceId())->returnTo(route('billing.index', ['status' => 'success']))"
                    class="flex h-full w-full cursor-default items-center justify-center px-2 py-1"
                >
                    <x-icons.loading x-show="processing" class="animate-spin" />
                    Subscribe
                </x-paddle-button>
            </x-ui.button>
        @else
            <x-ui.button
                x-data="{processing: false}"
                x-on:click="processing = true; setTimeout(() => { processing = false }, 1000)"
                as="a"
                href="{{ route('dashboard') }}"
                class="flex h-full w-full cursor-default items-center justify-center px-2 py-1"
            >
                <x-icons.loading x-show="processing" class="animate-spin" />
                Get started
            </x-ui.button>
        @endif
    </x-ui.card.footer>
</x-ui.card>
