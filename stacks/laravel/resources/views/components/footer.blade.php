<footer>
    <x-ui.container class="flex flex-col items-center justify-between md:flex-row">
        <div class="flex items-center justify-start">All rights reserved &copy; {{ date('Y') }}</div>
        <div class="flex items-center justify-end">
            <a href="{{ route('privacy') }}" class="hover:text-primary mr-3">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:text-primary mr-3">Terms of Use</a>
            <a href="https://x.com/saeed_vz" target="_blank" class="mr-2">
                <svg
                    class="text-foreground hover:text-primary size-4 fill-current"
                    role="img"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <title>X</title>
                    <path
                        d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"
                    />
                </svg>
            </a>
        </div>
    </x-ui.container>
</footer>
