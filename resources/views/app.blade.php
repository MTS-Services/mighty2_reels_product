<!DOCTYPE html>
<html lang="en" class="scroll-smooth light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}

    <title>{{ isset($title) ? $title . ' | ' . site_name() : site_name() }}</title>

    <link rel="icon" href="{{ favicon() }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ favicon() }}">
    @stack('links')

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
            --livewire-progress_bar_color: var(--color-orange-normal);
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

<body x-data @navigate.start="setTimeout(() => window.scrollTo({top: 0, behavior: 'smooth'}), 0)"
    class="bg-white min-h-screen font-inter">



    @if (Auth::check() && Route::is('admin.*'))
        @livewire('partials.admin-header', ['pageSlug' => $page_slug])
    @else
        {{-- @livewire('partials.user-header', ['pageSlug' => $page_slug]) --}}
        @include('include.user-header', ['page_slug' => $page_slug])
    @endif

    <main>
        {{ $slot }}
    </main>

    @livewire('partials.footer')

    <div id="navigation-loader" x-transition.opacity
        class="fixed inset-0 z-[1000000] flex items-center justify-center bg-accent-foreground/50 backdrop-blur-md">
        <div class="flex space-x-2">
            <div class="w-4 h-4 rounded-full bg-orange-normal animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.8s;"></div>
            <div class="w-4 h-4 rounded-full bg-orange-normal animate-[bounce-dot_1.2s_infinite]"
                style="animation-delay: -0.4s;"></div>
            <div class="w-4 h-4 rounded-full bg-orange-normal animate-[bounce-dot_1.2s_infinite]"></div>
        </div>
    </div>

    @fluxScripts

    {{-- <script>
        document.addEventListener('livewire:navigate', (event) => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigating', () => {
            document.getElementById('navigation-loader').classList.remove('hidden');
        });

        document.addEventListener('livewire:navigated', () => {
            document.getElementById('navigation-loader').classList.add('hidden');
        });
        document.addEventListener('livewire:initialized', () => {
            // Add this to your main JavaScript file or include it in the layout
            document.addEventListener('DOMContentLoaded', function() {
                // Handle filter loading states with enhanced UX
                let loadingTimeout;
                let isNavigating = false;

                // Listen for Livewire navigation start
                document.addEventListener('livewire:navigating', function() {
                    isNavigating = true;
                    showPageLoader();
                });

                // Listen for Livewire navigation end
                document.addEventListener('livewire:navigated', function() {
                    isNavigating = false;
                    hidePageLoader();
                });

                // Listen for Livewire requests
                document.addEventListener('livewire:init', function() {
                    Livewire.hook('request', ({
                        uri,
                        options,
                        payload,
                        respond,
                        succeed,
                        fail
                    }) => {
                        // Check if this is a filter-related request
                        const isFilterRequest = payload.updates.some(update => [
                            'searchTerm', 'selectedRooms', 'selectedCity',
                            'selectedType'
                        ].includes(update.property));

                        if (isFilterRequest && !isNavigating) {
                            // Show individual filter loading states
                            clearTimeout(loadingTimeout);
                            loadingTimeout = setTimeout(() => {
                                showPageLoader();
                            }, 200); // Slight delay to prevent flashing for quick requests
                        }
                    });

                    Livewire.hook('response', ({
                        response,
                        succeed,
                        fail
                    }) => {
                        clearTimeout(loadingTimeout);
                        if (!isNavigating) {
                            hidePageLoader();
                        }
                    });
                });

                // Enhanced page loader functions
                function showPageLoader() {
                    const existingLoader = document.getElementById('page-loader');
                    if (existingLoader) return;

                    const loader = document.createElement('div');
                    loader.id = 'page-loader';
                    loader.className =
                        'fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300';
                    loader.innerHTML = `
            <div class="bg-white rounded-xl p-8 shadow-2xl flex flex-col items-center gap-4 border border-orange-100">
                <div class="relative">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-100 border-t-orange-500"></div>
                    <div class="absolute inset-0 animate-pulse rounded-full h-12 w-12 border-4 border-transparent border-t-orange-300 opacity-50"></div>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-gray-700 font-medium">Loading content...</span>
                    <span class="text-gray-500 text-sm">Please wait</span>
                </div>
            </div>
        `;

                    // Add with fade-in effect
                    loader.style.opacity = '0';
                    document.body.appendChild(loader);

                    // Trigger fade-in
                    requestAnimationFrame(() => {
                        loader.style.opacity = '1';
                    });
                }

                function hidePageLoader() {
                    const loader = document.getElementById('page-loader');
                    if (loader) {
                        loader.style.opacity = '0';
                        setTimeout(() => {
                            if (loader.parentNode) {
                                loader.parentNode.removeChild(loader);
                            }
                        }, 300);
                    }
                }

                // Enhanced filter input debouncing with visual feedback
                function enhanceFilterInputs() {
                    const filterInputs = document.querySelectorAll(
                        '[wire\\:model*="searchTerm"], [wire\\:model*="selected"]');

                    filterInputs.forEach(input => {
                        let debounceTimer;

                        input.addEventListener('input', function() {
                            // Add loading state to input
                            this.classList.add('bg-orange-50', 'border-orange-200');

                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => {
                                this.classList.remove('bg-orange-50',
                                    'border-orange-200');
                            }, 500);
                        });
                    });
                }

                // Initialize enhancements
                enhanceFilterInputs();

                // Re-initialize after Livewire updates
                document.addEventListener('livewire:updated', function() {
                    enhanceFilterInputs();
                });

                // Handle URL parameter updates for better UX
                function updateURLWithFilters(filters) {
                    const url = new URL(window.location);

                    // Clear existing parameters
                    ['search', 'rooms', 'city', 'type'].forEach(param => {
                        url.searchParams.delete(param);
                    });

                    // Add non-empty filters
                    Object.entries(filters).forEach(([key, value]) => {
                        if (value && value.toString().trim()) {
                            const paramMap = {
                                searchTerm: 'search',
                                selectedRooms: 'rooms',
                                selectedCity: 'city',
                                selectedType: 'type'
                            };

                            const paramName = paramMap[key] || key;
                            url.searchParams.set(paramName, value);
                        }
                    });

                    // Update URL without page reload
                    window.history.pushState({}, '', url.toString());
                }

                // Listen for filter updates from Livewire components
                window.addEventListener('updateHomeFilters', function(event) {
                    console.log('Filters updated:', event.detail);

                    // Show visual feedback for filter updates
                    const filterContainer = document.querySelector('.search-area');
                    if (filterContainer) {
                        filterContainer.classList.add('animate-pulse');
                        setTimeout(() => {
                            filterContainer.classList.remove('animate-pulse');
                        }, 600);
                    }
                });

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function(event) {
                    // Let Livewire handle the URL parameter parsing
                    window.Livewire.dispatch('syncFiltersFromURL');
                });
            });

            // Utility function to show toast notifications for filter actions
            function showFilterToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

                const bgColors = {
                    info: 'bg-blue-500 text-white',
                    success: 'bg-green-500 text-white',
                    warning: 'bg-orange-500 text-white',
                    error: 'bg-red-500 text-white'
                };

                toast.classList.add(...bgColors[type].split(' '));
                toast.textContent = message;

                document.body.appendChild(toast);

                // Slide in
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full');
                });

                // Auto remove
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            }

            // Export for use in other scripts if needed
            window.FilterUtils = {
                showPageLoader,
                hidePageLoader,
                showFilterToast,
                updateURLWithFilters
            };
        });
    </script> --}}
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

        // Define page loader functions in the global scope
        function showPageLoader() {
            const existingLoader = document.getElementById('page-loader');
            if (existingLoader) return;

            const loader = document.createElement('div');
            loader.id = 'page-loader';
            loader.className =
                'fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300';
            loader.innerHTML = `
        <div class="bg-white rounded-xl p-8 shadow-2xl flex flex-col items-center gap-4 border border-orange-100">
            <div class="relative">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-100 border-t-orange-500"></div>
                <div class="absolute inset-0 animate-pulse rounded-full h-12 w-12 border-4 border-transparent border-t-orange-300 opacity-50"></div>
            </div>
            <div class="flex flex-col items-center gap-1">
                <span class="text-gray-700 font-medium">Loading content...</span>
                <span class="text-gray-500 text-sm">Please wait</span>
            </div>
        </div>
    `;

            // Add with fade-in effect
            loader.style.opacity = '0';
            document.body.appendChild(loader);

            // Trigger fade-in
            requestAnimationFrame(() => {
                loader.style.opacity = '1';
            });
        }

        function hidePageLoader() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => {
                    if (loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 300);
            }
        }

        // Utility function to show toast notifications for filter actions
        function showFilterToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            const bgColors = {
                info: 'bg-blue-500 text-white',
                success: 'bg-green-500 text-white',
                warning: 'bg-orange-500 text-white',
                error: 'bg-red-500 text-white'
            };

            toast.classList.add(...bgColors[type].split(' '));
            toast.textContent = message;

            document.body.appendChild(toast);

            // Slide in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full');
            });

            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Handle URL parameter updates for better UX
        function updateURLWithFilters(filters) {
            const url = new URL(window.location);

            // Clear existing parameters
            ['search', 'rooms', 'city', 'type'].forEach(param => {
                url.searchParams.delete(param);
            });

            // Add non-empty filters
            Object.entries(filters).forEach(([key, value]) => {
                if (value && value.toString().trim()) {
                    const paramMap = {
                        searchTerm: 'search',
                        selectedRooms: 'rooms',
                        selectedCity: 'city',
                        selectedType: 'type'
                    };

                    const paramName = paramMap[key] || key;
                    url.searchParams.set(paramName, value);
                }
            });

            // Update URL without page reload
            window.history.pushState({}, '', url.toString());
        }

        // Export for use in other scripts if needed
        window.FilterUtils = {
            showPageLoader,
            hidePageLoader,
            showFilterToast,
            updateURLWithFilters
        };

        document.addEventListener('livewire:initialized', () => {
            document.addEventListener('DOMContentLoaded', function() {
                // Handle filter loading states with enhanced UX
                let loadingTimeout;
                let isNavigating = false;

                // Listen for Livewire navigation start
                document.addEventListener('livewire:navigating', function() {
                    isNavigating = true;
                    showPageLoader();
                });

                // Listen for Livewire navigation end
                document.addEventListener('livewire:navigated', function() {
                    isNavigating = false;
                    hidePageLoader();
                });

                // Listen for Livewire requests
                document.addEventListener('livewire:init', function() {
                    Livewire.hook('request', ({
                        uri,
                        options,
                        payload,
                        respond,
                        succeed,
                        fail
                    }) => {
                        // Check if this is a filter-related request
                        const isFilterRequest = payload.updates.some(update => [
                            'searchTerm', 'selectedRooms', 'selectedCity',
                            'selectedType'
                        ].includes(update.property));

                        if (isFilterRequest && !isNavigating) {
                            // Show individual filter loading states
                            clearTimeout(loadingTimeout);
                            loadingTimeout = setTimeout(() => {
                                showPageLoader();
                            }, 200); // Slight delay to prevent flashing for quick requests
                        }
                    });

                    Livewire.hook('response', ({
                        response,
                        succeed,
                        fail
                    }) => {
                        clearTimeout(loadingTimeout);
                        if (!isNavigating) {
                            hidePageLoader();
                        }
                    });
                });

                // Enhanced filter input debouncing with visual feedback
                function enhanceFilterInputs() {
                    const filterInputs = document.querySelectorAll(
                        '[wire\\:model*="searchTerm"], [wire\\:model*="selected"]');

                    filterInputs.forEach(input => {
                        let debounceTimer;

                        input.addEventListener('input', function() {
                            // Add loading state to input
                            this.classList.add('bg-orange-50', 'border-orange-200');

                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => {
                                this.classList.remove('bg-orange-50',
                                    'border-orange-200');
                            }, 500);
                        });
                    });
                }

                // Initialize enhancements
                enhanceFilterInputs();

                // Re-initialize after Livewire updates
                document.addEventListener('livewire:updated', function() {
                    enhanceFilterInputs();
                });

                // Listen for filter updates from Livewire components
                window.addEventListener('updateHomeFilters', function(event) {
                    console.log('Filters updated:', event.detail);

                    // Show visual feedback for filter updates
                    const filterContainer = document.querySelector('.search-area');
                    if (filterContainer) {
                        filterContainer.classList.add('animate-pulse');
                        setTimeout(() => {
                            filterContainer.classList.remove('animate-pulse');
                        }, 600);
                    }
                });

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function(event) {
                    // Let Livewire handle the URL parameter parsing
                    window.Livewire.dispatch('syncFiltersFromURL');
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
