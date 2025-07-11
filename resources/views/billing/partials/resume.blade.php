<x-ui.modal title="Resume subscription">
    <x-slot:trigger>
        <x-ui.button>Resume subscription</x-ui.button>
    </x-slot>

    <p>{{ __('Your subscription will be resumed immediately.') }}</p>

    <p>{{ __('If your subscription has ended, you will be charged for the next billing cycle.') }}</p>

    <div class="mt-4 flex items-center justify-end gap-2">
        <form id="resume-form" action="{{ route('billing.resume') }}" method="post">
            @csrf
            <x-ui.button
                x-data="{processing: false}"
                x-on:click="processing = true; document.getElementById('resume-form').submit()"
                x-bind:disabled="processing"
            >
                <x-icons.loading x-show="processing" class="animate-spin" />
                {{ __('Resume subscription') }}
            </x-ui.button>
        </form>
    </div>
</x-ui.modal>
