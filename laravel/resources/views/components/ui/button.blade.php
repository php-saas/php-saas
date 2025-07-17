@props([
    'variant' => 'default',
    'size' => 'default',
    'as' => 'button',
])

@php
    $variants = [
        'default' => 'bg-primary text-primary-foreground hover:bg-primary/90 shadow-xs',
        'destructive' => 'bg-destructive hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 dark:bg-destructive/60 text-white shadow-xs',
        'outline' => 'bg-background hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 border shadow-xs',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80 shadow-xs',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50',
        'link' => 'text-primary underline-offset-4 hover:underline',
    ];

    $sizes = [
        'default' => 'h-9 px-4 py-2 has-[>svg]:px-3',
        'sm' => 'h-8 gap-1.5 rounded-md px-3 has-[>svg]:px-2.5',
        'lg' => 'h-10 rounded-md px-6 has-[>svg]:px-4',
        'xl' => 'h-11 rounded-md px-8 has-[>svg]:px-6',
        'icon' => 'size-9',
    ];

    $baseClasses = "cursor-default inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";

    $class = "$baseClasses {$variants[$variant]} {$sizes[$size]} " . ($attributes->get('class') ?? '');
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $class, 'data-slot' => 'button']) }}>
    {{ $slot }}
</{{ $as }}>
