@props([
    'variant' => 'default',
    'size' => 'default',
    'as' => 'button',
])

@php
    $variants = [
        'default' => 'border-primary/40 dark:border-primary bg-primary/10 dark:bg-primary/30 text-primary/90 dark:text-foreground/90 hover:bg-primary/20 dark:hover:bg-primary/40 focus-visible:ring-primary/20 dark:focus-visible:ring-primary/40 border shadow-lg shadow-xs',
        'destructive' => 'border-destructive/40 dark:bg-destructive/30 bg-destructive/10 text-destructive/70 dark:text-foreground/90 hover:bg-destructive/20 dark:hover:bg-destructive/40 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 border shadow-xs',
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

    $baseClasses = 'cursor-pointer inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*="size-"])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive';

    $class = "$baseClasses {$variants[$variant]} {$sizes[$size]} " . ($attributes->get('class') ?? '');
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $class, 'data-slot' => 'button']) }}>
    {{ $slot }}
</{{ $as }}>
