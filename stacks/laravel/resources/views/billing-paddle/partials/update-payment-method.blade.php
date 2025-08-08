<x-ui.button
    x-data="{processing: false}"
    x-on:click="processing = true;"
    as="a"
    href="{{ route('billing.update-payment-method') }}"
    x-bind:disabled="processing"
    x-bind:class="{'pointer-events-none opacity-50': processing}"
    variant="outline"
>
    <x-icons.loading x-show="processing" class="animate-spin" />
    Update payment method
</x-ui.button>
