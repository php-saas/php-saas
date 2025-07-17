<div
    {{ $attributes->merge(['class' => 'group flex flex-1 list-none items-center justify-center gap-1']) }}
    data-slot="navigation-menu-list"
>
    {{ $slot }}
</div>
