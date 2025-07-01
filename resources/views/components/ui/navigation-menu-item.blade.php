<a
    data-slot="navigation-menu-item"
    {{ $attributes->merge(['class' => 'text-sm font-medium text-muted-foreground hover:text-foreground rounded-md py-2 px-4']) }}
>
    {{ $slot }}
</a>
