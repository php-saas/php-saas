@if (session()->has('success'))
    <x-ui.alert class="flex items-center gap-2">
        <x-icons.check-circle class="text-green-600" />
        {{ session()->get('success') }}
    </x-ui.alert>
@endif

@if (session()->has('error'))
    <x-ui.alert class="flex items-center gap-2">
        <x-icons.circle-x class="text-red-600" />
        {{ session()->get('error') }}
    </x-ui.alert>
@endif
