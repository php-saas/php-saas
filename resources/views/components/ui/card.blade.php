<div
    {{ $attributes->merge(['class' => 'bg-card text-card-foreground flex flex-col rounded-xl border shadow-xs']) }}
    data-slot="card"
>
    {{ $slot }}
</div>
