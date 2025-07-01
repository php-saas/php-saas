<div
    {{ $attributes->merge(['class' => 'flex items-center border-t p-4']) }}
    data-slot="card-footer"
>
    {{ $slot }}
</div>
