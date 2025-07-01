@props([
    'active',
    'class' => '',
])

@php
    $default = $active ?? 'tab-1';
@endphp

<div x-data="{ selectedTab: '{{ $default }}' }" class="{{ $class }} flex flex-col gap-2" data-slot="tabs">
    {{ $slot }}
</div>
