<div
    {{ $attributes->merge(['class' => 'bg-card text-card-foreground flex w-80 flex-col justify-between gap-6 rounded-xl border py-6 text-left shadow-sm']) }}
    data-slot="card"
>
    {{ $slot }}
</div>
