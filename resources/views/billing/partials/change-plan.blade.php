<x-ui.button
    x-data="{processing: false}"
    x-on:click="processing = true; setTimeout(() => { processing = false }, 1000)"
    as="a"
    href="{{ route('billing.update-payment-method') }}"
    x-bind:disabled="processing"
    x-bind:class="{'pointer-events-none opacity-50': processing}"
>
    <x-icons.loading x-show="processing" class="animate-spin" />
    Change subscription plan
</x-ui.button>
