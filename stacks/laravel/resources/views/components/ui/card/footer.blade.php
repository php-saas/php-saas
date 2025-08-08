@props([
    'class' => '',
])

<div data-slot="card-footer" {{ $attributes->merge(['class' => 'mt-auto flex items-center px-6 [.border-t]:pt-6']) }}>
    {{ $slot }}
</div>
