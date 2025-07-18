@props([
    'class' => '',
])

<div data-slot="card-content" {{ $attributes->merge(['class' => 'px-6 space-y-3']) }}>
    {{ $slot }}
</div>
