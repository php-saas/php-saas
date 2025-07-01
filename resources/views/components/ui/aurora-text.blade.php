@props([
    'class' => '',
    'colors' => ['#FF0080', '#7928CA', '#0070F3', '#38bdf8'],
    'speed' => 1,
])

@php
    $gradient = 'linear-gradient(135deg, ' . implode(',', $colors) . ', ' . $colors[0] . ')';
    $duration = 10 / floatval($speed);
@endphp

<span class="{{ $class }} relative inline-block">
    <span class="sr-only">{{ $slot }}</span>
    <span
        x-data
        x-init="
            $el.style.backgroundImage = '{{ $gradient }}'
            $el.style.animationDuration = '{{ $duration }}s'
        "
        class="auroraGradient 10s infinite animate-aurora relative bg-[length:200%_auto] bg-clip-text text-transparent ease-in-out"
        aria-hidden="true"
    >
        {{ $slot }}
    </span>
</span>
