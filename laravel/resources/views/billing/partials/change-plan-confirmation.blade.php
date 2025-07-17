<x-ui.modal title="Change Plan">
    <x-slot:trigger>
        <x-ui.button class="w-full">
            {{ __('Change to this plan') }}
        </x-ui.button>
    </x-slot>

    <p>
        {{ __('Are you sure you want to change your subscription plan to :plan with :billing billing cycle?', ['plan' => $newPlan->name, 'billing' => $newPlan->billing]) }}
    </p>

    <div class="mt-4 flex items-center justify-end gap-2">
        <form id="change-plan-{{ $newPlan->priceId }}" action="{{ route('billing.swap') }}" method="post">
            @csrf
            <input type="hidden" name="price_id" value="{{ $newPlan->priceId }}" />
            <x-ui.button
                x-data="{processing: false}"
                x-on:click="processing = true; document.getElementById('change-plan-{{ $newPlan->priceId }}').submit()"
                x-bind:disabled="processing"
            >
                <x-icons.loading x-show="processing" class="animate-spin" />
                {{ __('Change Plan') }}
            </x-ui.button>
        </form>
    </div>
</x-ui.modal>
