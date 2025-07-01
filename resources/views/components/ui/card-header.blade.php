<div
    {{ $attributes->merge(['class' => 'flex flex-col gap-1.5 border-b p-4']) }}
    data-slot="card-header"
>
    {{ $slot }}
</div>
