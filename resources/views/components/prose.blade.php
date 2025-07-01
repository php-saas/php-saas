<div
    {{ $attributes->merge(['class' => 'max-w-none prose prose-lg prose-headings:text-foreground text-muted-foreground prose-a:text-primary prose-strong:text-foreground']) }}
>
    {{ $slot }}
</div>
