<x-ui.modal title="Cancel subscription">
    <x-slot:trigger>
        <x-ui.button variant="destructive">Cancel</x-ui.button>
    </x-slot>

    <p>{{ __('Are you sure you want to cancel your subscription?') }}</p>

    <div class="mt-4 flex items-center justify-end gap-2">
        <form id="cancel-form" action="{{ route('billing.destroy') }}" method="post">
            @csrf
            @method('delete')
            <x-ui.button
                x-data="{processing: false}"
                x-on:click="processing = true; document.getElementById('cancel-form').submit()"
                variant="destructive"
                x-bind:disabled="processing"
            >
                <x-icons.loading x-show="processing" class="animate-spin" />
                {{ __('Cancel my subscription') }}
            </x-ui.button>
        </form>
    </div>
</x-ui.modal>
