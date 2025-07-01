@props([
    'name',
    'class' => '',
])

<div x-show="selectedTab === '{{ $name }}'" x-cloak class="{{ $class }} outline-none" data-slot="tabs-content">
    {{ $slot }}
</div>
