<div
    {{ $attributes->merge(['class' => 'group/navigation-menu relative flex max-w-max flex-1 items-center justify-center']) }}
    data-slot="navigation-menu"
>
    {{ $slot }}
</div>
