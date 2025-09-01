<!DOCTYPE html>
<html lang="en" class="scroll-smooth light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' | ' . site_name() : site_name() }}</title>

    <link rel="icon" href="{{ favicon() }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ favicon() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance()

    <style>
        @keyframes bounce-dot {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        :root {
            --livewire-progress_bar_color: #C7AE6A;
        }

        #nprogress .bar {
            background: var(--livewire-progress_bar_color) !important;
        }

        #nprogress .peg {
            box-shadow: 0 0 10px var(--livewire-progress_bar_color), 0 0 5px var(--livewire-progress_bar_color) !important;
        }

        #nprogress .spinner-icon {
            border-top-color: var(--livewire-progress_bar_color) !important;
            border-left-color: var(--livewire-progress_bar_color) !important;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('sweetalert2', (event) => {
                showAlert(event.type, event.message);
            });
        });
    </script>

    @stack('styles')
</head>

<body x-data @navigate.start="setTimeout(() => window.scrollTo({top: 0, behavior: 'smooth'}), 0)" class="bg-white">


    @auth
        @livewire('partials.auth.header', ['pageSlug' => $page_slug])
    @else
        @livewire('partials.guest.header', ['pageSlug' => $page_slug])
    @endauth

    <main class="container max-w-[1200px] w-full mx-auto p-4 mt-5 font-playfair">
        {{ $slot }}
    </main>

    @auth
        @livewire('partials.auth.footer')
    @else
        @livewire('partials.guest.footer')
    @endauth


    <div id="navigation-loader" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-accent-foreground/50 backdrop-blur-md">
        <div class="flex space-x-2">
            <div class="w-4 h-4 rounded-full bg-[#C7AE6A] animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.8s;"></div>
            <div class="w-4 h-4 rounded-full bg-[#C7AE6A] animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.4s;"></div>
            <div class="w-4 h-4 rounded-full bg-[#C7AE6A] animate-[bounce-dot_1.2s_infinite]"></div>
        </div>
    </div>

    @fluxScripts

    <script>
        document.addEventListener('livewire:navigate', (event) => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigating', () => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigated', () => {
            document.getElementById('navigation-loader').classList.add('hidden');
        });
    </script>
    @stack('scripts')
</body>

</html>
