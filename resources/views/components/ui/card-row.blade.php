<div
    {{ $attributes->merge(['class' => 'flex items-center justify-between px-4 py-2']) }}
    data-slot="card-row"
>
    {{ $slot }}
</div>
