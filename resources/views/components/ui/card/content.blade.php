@props([
    'class' => ''
])

<div data-slot="card-content" {{ $attributes->merge(['class' => 'px-6']) }}>
    {{ $slot }}
</div>
