@props([
    'title' => '',
])

<div x-data="{ modalOpen: false }" @keydown.escape.window="modalOpen = false" class="relative z-50 w-full">
    <div class="w-full" @click="modalOpen=true">
        {{ $trigger }}
    </div>

    <template x-teleport="body">
        <div
            x-show="modalOpen"
            class="fixed top-0 left-0 z-[99] flex h-screen w-screen items-center justify-center"
            x-cloak
        >
            <div
                x-show="modalOpen"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-300 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="modalOpen=false"
                class="bg-opacity-40 absolute inset-0 h-full w-full bg-black/50"
            ></div>
            <div
                x-show="modalOpen"
                x-trap.inert.noscroll="modalOpen"
                x-transition:enter="duration-300 ease-out"
                x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                class="bg-background relative w-full gap-4 rounded-lg border p-6 shadow-lg duration-200 sm:max-w-lg"
            >
                <div class="flex items-center justify-between pb-2">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    <button
                        @click="modalOpen=false"
                        class="text-foreground hover:text-foreground/50 absolute top-0 right-0 mt-5 mr-5 flex h-8 w-8 items-center justify-center rounded-full"
                    >
                        <svg
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative w-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>
