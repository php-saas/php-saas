@props([
    'name',
    'class' => '',
])

<button
    type="button"
    x-on:click="selectedTab = '{{ $name }}'"
    :class="selectedTab === '{{ $name }}'
        ? 'bg-background text-foreground shadow-sm dark:text-foreground dark:bg-input/30 border-input'
        : 'text-foreground dark:text-muted-foreground border-transparent'"
    class="focus-visible:ring-ring/50 {{ $class }} inline-flex h-[calc(100%-1px)] items-center justify-center gap-1.5 rounded-md border px-3 py-1 text-sm font-medium transition-[color,box-shadow] focus-visible:ring-[3px] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
    data-slot="tabs-trigger"
>
    {{ $slot }}
</button>
