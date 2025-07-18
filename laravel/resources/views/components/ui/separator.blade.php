@props([
    'orientation' => 'horizontal',
    'decorative' => true,
    'class' => '',
])

<div
    data-slot="separator-root"
    role="{{ $decorative ? 'presentation' : 'separator' }}"
    aria-orientation="{{ $orientation }}"
    class="{{ $class }} bg-border {{ $orientation === 'horizontal' ? 'h-px w-full' : 'h-full w-px' }} shrink-0"
></div>
