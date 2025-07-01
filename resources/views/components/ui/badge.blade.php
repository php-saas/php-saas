@props([
    'variant' => 'default',
    'as' => 'span',
])

@php
    $base = 'focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive inline-flex w-fit shrink-0 items-center justify-center gap-1 overflow-auto rounded-md border px-2 py-0.5 text-xs font-medium whitespace-nowrap transition-[color,box-shadow] focus-visible:ring-[3px] [&>svg]:pointer-events-none [&>svg]:size-3';

    $variants = [
        'default' => 'border-primary/40 dark:border-primary bg-primary/10 dark:bg-primary/20 text-primary/90 dark:text-foreground/90 border',
        'success' => 'border-success/40 dark:border-success/60 bg-success/10 dark:bg-success/20 text-success/90 dark:text-foreground/90 border',
        'info' => 'border-info/40 dark:border-info/60 bg-info/10 dark:bg-info/20 text-info/90 dark:text-foreground/90 border',
        'warning' => 'border-warning/40 dark:border-warning/60 bg-warning/10 dark:bg-warning/20 text-warning/90 dark:text-foreground/90 border',
        'danger' => 'border-destructive/40 dark:border-destructive/60 bg-destructive/10 dark:bg-destructive/20 text-destructive/90 dark:text-foreground/90 border',
        'destructive' => 'border-destructive/40 dark:border-destructive/60 bg-destructive/10 dark:bg-destructive/20 text-destructive/90 dark:text-foreground/90 border',
        'gray' => 'border-gray/40 dark:border-gray/60 bg-gray/10 dark:bg-gray/20 text-gray/90 dark:text-foreground/90 border',
        'outline' => 'text-foreground [a&]:hover:bg-accent [a&]:hover:text-accent-foreground',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($attributes->get('class') ?? '');
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes, 'data-slot' => 'badge']) }}>
    {{ $slot }}
</{{ $as }}>
