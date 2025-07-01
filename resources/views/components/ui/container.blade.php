<div
    {{ $attributes->merge(['class' => 'container max-w-7xl mx-auto space-y-5 px-4 py-5']) }}
    data-slot="ui-container"
>
    {{ $slot }}
</div>
