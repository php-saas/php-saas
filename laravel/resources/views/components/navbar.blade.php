<div class="border-sidebar-border/80 bg-background fixed top-0 right-0 left-0 z-20 border-b">
    {{-- top bar --}}
    <div x-data="{ menu: false }" class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
        <a href="/">
            <x-logo class="size-8" />
        </a>
        {{-- mobile menu --}}
        <div :class="{'block!': menu}" class="bg-background fixed top-16 right-0 bottom-0 left-0 z-10 hidden p-4">
            <div class="flex flex-col space-y-2 text-center">
                <x-ui.navigation-menu-item href="{{ route('home') }}">
                    {{ __('Home') }}
                </x-ui.navigation-menu-item>
                <x-ui.navigation-menu-item href="#" target="_blank">
                    {{ __('Documentation') }}
                </x-ui.navigation-menu-item>
                <!--<php-saas:billing>-->
                <x-ui.navigation-menu-item href="/#pricing" x-on:click="menu = false">
                    {{ __('Pricing') }}
                </x-ui.navigation-menu-item>
                <!--</php-saas:billing>-->
                <x-ui.navigation-menu-item href="{{ route('contact') }}">
                    {{ __('Contact') }}
                </x-ui.navigation-menu-item>
                @if (auth()->check())
                    <x-ui.button
                        as="a"
                        href="{{ route('dashboard') }}"
                        class="absolute right-4 bottom-4 left-4"
                        x-on:click="menu = false"
                    >
                        {{ __('Dashboard') }}
                    </x-ui.button>
                @else
                    <x-ui.button
                        variant="outline"
                        as="a"
                        href="{{ route('login') }}"
                        class="absolute right-4 bottom-4 left-4"
                        x-on:click="menu = false"
                    >
                        Login
                    </x-ui.button>
                @endif
            </div>
        </div>
        {{-- desktop menu --}}
        <div class="ml-6 hidden h-full w-full items-center justify-between md:flex">
            <x-ui.navigation-menu>
                <x-ui.navigation-menu-list>
                    <x-ui.navigation-menu-item
                        href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-foreground! font-semibold' : '' }}"
                    >
                        {{ __('Home') }}
                    </x-ui.navigation-menu-item>
                    <x-ui.navigation-menu-item href="#" target="_blank">
                        {{ __('Documentation') }}
                    </x-ui.navigation-menu-item>
                    <!--<php-saas:billing>-->
                    <x-ui.navigation-menu-item href="/#pricing">
                        {{ __('Pricing') }}
                    </x-ui.navigation-menu-item>
                    <!--</php-saas:billing>-->
                    <x-ui.navigation-menu-item
                        href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'text-foreground! font-semibold' : '' }}"
                    >
                        {{ __('Contact') }}
                    </x-ui.navigation-menu-item>
                </x-ui.navigation-menu-list>
            </x-ui.navigation-menu>
            @if (auth()->check())
                <x-ui.button as="a" href="{{ route('dashboard') }}">
                    {{ __('Dashboard') }}
                </x-ui.button>
            @else
                <x-ui.button variant="outline" as="a" href="{{ route('login') }}">Login</x-ui.button>
            @endif
        </div>
        <div class="ml-auto flex items-center space-x-2 md:hidden">
            <x-ui.button variant="ghost" size="icon" @click="menu = !menu">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    :class="{'hidden!': menu}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                    />
                </svg>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="hidden size-6"
                    :class="{'block!': menu}"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </x-ui.button>
        </div>
    </div>
</div>
