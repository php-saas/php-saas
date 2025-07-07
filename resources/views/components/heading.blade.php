@props([
    'title' => '',
    'description' => '',
])
<div class="space-y-0.5">
    <h2 class="text-xl font-semibold tracking-tight">{{ $title }}</h2>
    @if ($description)
        <p class="text-muted-foreground text-sm">{{ $description }}</p>
    @endif
</div>
