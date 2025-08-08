<div
    {{ $attributes->merge(['class' => 'bg-muted text-muted-foreground inline-flex h-9 w-fit items-center justify-center rounded-lg p-[3px] mx-auto']) }}
    data-slot="tabs-list"
>
    {{ $slot }}
</div>
