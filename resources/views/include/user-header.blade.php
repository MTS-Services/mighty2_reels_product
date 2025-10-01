<!-- Header Include for Non-Admin Pages -->
<div class="sticky top-0 left-0 z-50" x-data="{ openProfileDropdown: false }">
    <div class="w-full">
        <header class="bg-orange-light/20 shadow backdrop-blur-lg">
            <div class="container max-w-[1200px]  mx-auto py-0 sm:py-3 sm:px-4  md:px-6 lg:px-8">
                <nav class="items-center hidden sm:flex justify-between gap-4">
                    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                        <span class="text-[18px] lg:text-[24px] text-orange-normal capitalize">{{ site_name() }}</span>
                    </a>

                    {{-- Desktop Navigation --}}
                    <div class="items-center gap-9 sm:w-[250px] md:w-[350px] lg:w-[500px] xl:w-[680px] hidden sm:flex justify-between">
                        @if ($page_slug == 'home' || $page_slug == 'favorites' || $page_slug == 'product')
                            <x-button href="{{ route('favorites') }}" wire:navigate variant="secondary" class="!rounded-full !text-[12px] lg:text-[18px]! sm:px-1! lg:px-4! sm:gap-1! lg:gap-3!">
                                <flux:icon name="heart" class="fill-orange-normal w-4! h-4! lg:w-6! lg:h-6!" /> Saved Reels
                            </x-button>
                        @endif

                        {{-- Filter Button for Home Page --}}
                        @if ($page_slug == 'home')
                            <div class="flex items-center gap-4 justify-end">
                                <button @click="$dispatch('open-search-modal')"
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-normal/10 text-orange-600 hover:bg-orange-normal/20 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61z" />
                                    </svg>
                                </button>
                                <button @click="$dispatch('open-filter-modal')"
                                    class="bg-orange-normal/10 text-orange-600 hover:bg-orange-normal/20 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                                    </svg>
                                </button>
                                {{-- Updated Reset Button Logic --}}
                                @if (!empty(request('search')) || !empty(request('rooms')) || !empty(request('city')) || !empty(request('type')))
                                    <button @click="$dispatch('reset-all-filters')"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="p-2 text-gray-700 hover:text-orange-normal transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Desktop User Profile/Auth --}}
                    @if (Auth::check())
                        <div class="hidden sm:flex items-center gap-4 relative py-2 {{ $page_slug == 'favorites' || $page_slug == 'profile' ? 'ml-auto' : '' }}"
                            x-on:click.away="openProfileDropdown = false">

                            <div class="flex items-center gap-4 cursor-pointer"
                                x-on:click="openProfileDropdown = !openProfileDropdown">
                                <img src="{{ asset('assets/images/profile.jpg') }}" alt="Name"
                                    class="w-11 h-11 rounded-full object-cover">
                                <div class="flex flex-col">
                                    <span class="text-lg font-medium text-black">{{ user()->full_name }}</span>
                                    <span class="text-xs text-black">{{ user()->email }}</span>
                                </div>
                            </div>

                            <div x-show="openProfileDropdown" x-cloak
                                x-transition:enter="transition-all duration-300 ease-in-out"
                                x-transition:enter-start="grid-rows-[0fr] opacity-0"
                                x-transition:enter-end="grid-rows-[1fr] opacity-100"
                                x-transition:leave="transition-all duration-300 ease-in-out"
                                x-transition:leave-start="grid-rows-[1fr] opacity-100"
                                x-transition:leave-end="grid-rows-[0fr] opacity-0"
                                class="absolute top-full right-0 mt-2 grid bg-white backdrop-blur-lg shadow-lg rounded-lg overflow-hidden z-50">
                                <div class="p-4 space-y-2 overflow-hidden relative z-50">
                                    <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                        href="{{ route('profile') }}" wire:navigate>
                                        <flux:icon name="user-circle" /> Profile
                                    </x-button>
                                    @if (is_admin())
                                        <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                            href="{{ route('admin.products') }}" wire:navigate>
                                            <flux:icon name="box" /> Dashboard
                                        </x-button>
                                    @endif
                                    <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                        @click="$dispatch('logout-requested')">
                                        <flux:icon name="power" /> Logout
                                    </x-button>

                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="px-4 hidden sm:block {{ Route::is('login') || Route::is('register') || Route::is('product') ? 'ml-auto' : '' }} gap-2">
                            @if (Route::is('login'))
                                <x-button route="register">
                                    <flux:icon name="user-plus" />
                                    Register
                                </x-button>
                            @elseif (Route::is('register'))
                                <x-button variant="secondary" route="login">
                                    <flux:icon name="log-in" />
                                    Login
                                </x-button>
                            @else
                                <x-button variant="secondary" route="login">
                                    <flux:icon name="log-in" />
                                </x-button>
                                <x-button route="register">
                                    <flux:icon name="user-plus" />
                                </x-button>
                            @endif
                        </div>
                    @endif

                </nav>
            </div>
        </header>

        {{-- Sidebar --}}
        {{-- <aside class="fixed inset-0 z-[60]" x-show="sidebarOpen" x-cloak
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
            @click.outside="sidebarOpen = false">
            <div class="relative w-full h-full">
                <button class="absolute inset-0 bg-orange-light/20 backdrop-blur-lg"
                    x-on:click="sidebarOpen = false"></button>
                <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto flex flex-col">
                    <div class="flex items-center justify-between px-4 py-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-start gap-4">
                            <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                            <span class="text-[26px] text-orange-normal capitalize">{{ site_name() }}</span>
                        </a>
                        <x-button x-on:click="sidebarOpen = false" size="sm" variant="secondary">
                            <flux:icon name="x-mark" />
                        </x-button>
                    </div>
                    <flux:separator variant="subtle" class="my-2" />
                    <div class="px-4 flex flex-col items-start gap-4 flex-1">
                        @if (Auth::check())
                            <x-button href="{{ route('profile') }}" wire:navigate
                                class="text-black !py-1 w-full justify-start {{ Route::is('profile') ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                                size="lg" linkText>
                                <flux:icon name="user-circle" /> Profile
                            </x-button>

                            @if (is_admin())
                                <x-button class="text-black !py-1 w-full !justify-start" size="lg"
                                    href="{{ route('admin.products') }}" wire:navigate linkText>
                                    <flux:icon name="box" /> Dashboard
                                </x-button>
                            @endif

                            <x-button href="{{ route('favorites') }}"
                                class="text-black !px-2 !py-1 w-full justify-start {{ Route::is('favorites') ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                                size="lg" linkText>
                                <flux:icon name="heart" class="fill-orange-normal text-orange-normal" /> Saved Reels
                            </x-button>

                            <flux:spacer />
                            <flux:separator variant="subtle" class="my-2" />
                            <x-button class="w-full mb-3" variant="secondary" size="lg" wire:click="logout">
                                <flux:icon name="power" /> Logout
                            </x-button>
                        @else
                            @if (Route::is('login'))
                                <x-button class="w-full !justify-start" variant="secondary" size="lg"
                                    route="register" wire:navigate>
                                    <flux:icon name="user-plus" /> Register
                                </x-button>
                            @elseif (Route::is('register'))
                                <x-button class="w-full !justify-start" variant="secondary" size="lg"
                                    route="login" wire:navigate>
                                    <flux:icon name="log-in" /> Login
                                </x-button>
                            @else
                                <x-button class="w-full !justify-start" variant="secondary" size="lg"
                                    route="login" wire:navigate>
                                    <flux:icon name="log-in" /> Login
                                </x-button>
                                <x-button class="w-full !justify-start" variant="secondary" size="lg"
                                    route="register" wire:navigate>
                                    <flux:icon name="user-plus" /> Register
                                </x-button>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        </aside> --}}

        {{-- Desktop search area for non-mobile --}}
        {{-- @if ($page_slug == 'home') --}}
        {{-- <div
                class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[564px] mt-2 space-y-2 search-area hidden lg:block">
                <div class="flex items-center gap-4">
                    <div class="flex-1 flex items-center gap-1">
                        <x-button variant="secondary" class="!rounded-full lg:hidden flex-shrink-0"
                            x-on:click="$wire.openFilterModal()">
                            <flux:icon name="funnel" />
                        </x-button>
                    </div>
                </div>
            </div> --}}
        {{-- @endif --}}
    </div>
</div>
