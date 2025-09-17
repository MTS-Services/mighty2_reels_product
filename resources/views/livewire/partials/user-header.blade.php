<div>
    <div x-data="{
        openProfileDropdown: @entangle('openProfileDropdown').live,
        sidebarOpen: @entangle('sidebarOpen').live,
        filterOpen: @entangle('filterOpen').live
    }">
        <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg">
            <div class="container max-w-[1200px] mx-auto py-3 px-4 md:px-6 lg:px-8">
                <nav class="flex items-center justify-between gap-4">
                    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                        <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                    </a>

                    <div class="items-center gap-9 -mr-10 hidden lg:flex justify-between">
                        @if ($pageSlug == 'home' || $pageSlug == 'favorites' || $pageSlug == 'product')
                            <x-button href="{{ route('favorites') }}" variant="secondary" class="!rounded-full">
                                <flux:icon name="heart" class="fill-orange-normal" /> Saved Reels
                            </x-button>
                        @endif

                        @if ($pageSlug == 'home')
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Rooms Filter -->
                                <div class="relative">
                                    <select wire:model.live="selectedRooms"
                                        class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2 pr-10"
                                        wire:loading.class="opacity-50" wire:target="selectedRooms">
                                        <option value="" selected>All Rooms</option>
                                        @for ($room = 1; $room <= $maxRooms; $room++)
                                            <option value="{{ $room }}">
                                                {{ $room . ' ' . ($room > 1 ? 'Rooms' : 'Room') }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div wire:loading wire:target="selectedRooms"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <div
                                            class="animate-spin rounded-full h-4 w-4 border-2 border-orange-normal border-t-transparent">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cities Filter -->
                                <div class="relative">
                                    <select wire:model.live="selectedCity"
                                        class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2 pr-10"
                                        wire:loading.class="opacity-50" wire:target="selectedCity">
                                        <option value="" selected>All Cities</option>
                                        @foreach ($cities as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="selectedCity"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <div
                                            class="animate-spin rounded-full h-4 w-4 border-2 border-orange-normal border-t-transparent">
                                        </div>
                                    </div>
                                </div>

                                <!-- Type Filter -->
                                <div class="relative">
                                    <select wire:model.live="selectedType"
                                        class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2 pr-10"
                                        wire:loading.class="opacity-50" wire:target="selectedType">
                                        <option value="" selected>All Types</option>
                                        <option value="{{ \App\Models\Product::TYPE_RENT }}">Rent</option>
                                        <option value="{{ \App\Models\Product::TYPE_SALE }}">Sale</option>
                                    </select>
                                    <div wire:loading wire:target="selectedType"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <div
                                            class="animate-spin rounded-full h-4 w-4 border-2 border-orange-normal border-t-transparent">
                                        </div>
                                    </div>
                                </div>

                                <!-- Reset Button -->
                                <div class="flex justify-center items-center">
                                    <button wire:click="resetAllFilters"
                                        wire:loading.class="opacity-50 cursor-not-allowed" wire:target="resetAllFilters"
                                        class="p-2 text-gray-700 focus:outline-none transition-transform duration-300 ease-in-out hover:text-orange-500 relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                            wire:loading.class.remove="animate-none" wire:loading.class="animate-spin"
                                            wire:target="resetAllFilters">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (Auth::check())
                        <div class="hidden lg:flex items-center gap-4 relative py-2"
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
                                class="absolute top-full right-0 mt-2 grid bg-white backdrop-blur-lg shadow-lg rounded-lg overflow-hidden">
                                <div class="p-4 space-y-2 overflow-hidden">
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
                                        wire:click="logout">
                                        <flux:icon name="power" /> Logout
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="px-4 flex {{ Route::is('login') || Route::is('register') ? 'ml-auto' : '' }} gap-2 ">
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

                    <div class="flex items-center gap-4">
                        <x-button x-on:click="sidebarOpen = true" size="sm" variant="secondary"
                            class="lg:hidden">
                            <flux:icon name="bars-4" />
                        </x-button>
                    </div>
                </nav>
            </div>
        </header>

        {{-- Sidebar --}}
        <aside class="fixed inset-0 z-[50000]!" x-show="sidebarOpen" x-cloak
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
            @click.outside="sidebarOpen = false">
            <div class="relative w-full h-full ">
                <button class="absolute inset-0 bg-orange-light/20 backdrop-blur-lg"
                    x-on:click="sidebarOpen = false"></button>
                <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto flex flex-col z-[500]">
                    <div class="flex items-center justify-between px-4 py-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-start gap-4">
                            <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                            <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                        </a>
                        <x-button x-on:click="sidebarOpen = false" size="sm" variant="secondary">
                            <flux:icon name="x-mark" />
                        </x-button>
                    </div>
                    <flux:separator variant="subtle" class="my-2" />
                    <div class="px-4 flex flex-col items-start gap-4 flex-1">
                        <x-button href="{{ route('profile') }}" wire:navigate
                            class="text-black !py-1 w-full justify-start  {{ $pageSlug == 'profile' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                            size="lg" linkText>
                            <flux:icon name="user-circle" /> Profile
                        </x-button>
                        @if (is_admin())
                            <x-button class="text-black !py-1 w-full !justify-start " size="lg"
                                href="{{ route('admin.products') }}" wire:navigate linkText>
                                <flux:icon name="box" /> Dashboard
                            </x-button>
                        @endif

                        <x-button href="{{ route('favorites') }}"
                            class="text-black !px-2 !py-1 w-full justify-start {{ $pageSlug == 'reels' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                            size="lg" linkText>
                            <flux:icon name="heart" class="fill-orange-normal text-orange-normal" /> Saved Reels
                        </x-button>
                        <flux:spacer />
                        <flux:separator variant="subtle" class="my-2" />
                        <x-button class="w-full mb-3" variant="secondary" size="lg" wire:click="logout">
                            <flux:icon name="power" /> Logout
                        </x-button>
                    </div>
                </div>
            </div>
        </aside>

        @if ($pageSlug == 'home')
            {{-- Search & Filters Area --}}
            <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[564px] mt-7 space-y-2 search-area"
                x-data="{
                    filterOpen: @entangle('filterOpen').live,
                }">

                <div class="flex items-center gap-4">
                    {{-- The main flex container for the search bar, filter options, and funnel icon. --}}
                    <div class="flex-1 flex items-center gap-4">

                        {{-- Search form --}}
                        <div class="flex-1 relative" x-show="!filterOpen || window.innerWidth >= 1024"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <input type="search" wire:model.live.debounce.500ms="searchTerm"
                                placeholder="Search for products..."
                                class="w-full px-4 py-2 pr-10 rounded-full outline outline-orange-normal text-orange-400 focus:ring-2 focus:ring-orange-normal"
                                wire:loading.class="opacity-50" wire:target="searchTerm" />
                            <div wire:loading wire:target="searchTerm"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-2 border-orange-normal border-t-transparent">
                                </div>
                            </div>
                        </div>

                        {{-- Filters and Refresh Button --}}
                        <div x-show="filterOpen && window.innerWidth < 1024" x-cloak
                            class="flex-1 flex items-center gap-2"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">

                            <!-- Mobile Rooms Filter -->
                            <div class="relative flex-1">
                                <select wire:model.live="selectedRooms"
                                    class="select w-full focus:outline-none border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full px-2 py-1 pr-6"
                                    wire:loading.class="opacity-50" wire:target="selectedRooms">
                                    <option value="" selected>All Rooms</option>
                                    @for ($room = 1; $room <= $maxRooms; $room++)
                                        <option value="{{ $room }}">
                                            {{ $room . ' ' . ($room > 1 ? 'Rooms' : 'Room') }}
                                        </option>
                                    @endfor
                                </select>
                                <div wire:loading wire:target="selectedRooms"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                    <div
                                        class="animate-spin rounded-full h-3 w-3 border border-orange-normal border-t-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Cities Filter -->
                            <div class="relative flex-1">
                                <select wire:model.live="selectedCity"
                                    class="select w-full focus:outline-none border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full px-2 py-1 pr-6"
                                    wire:loading.class="opacity-50" wire:target="selectedCity">
                                    <option value="" selected>All Cities</option>
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="selectedCity"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                    <div
                                        class="animate-spin rounded-full h-3 w-3 border border-orange-normal border-t-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Type Filter -->
                            <div class="relative flex-1">
                                <select wire:model.live="selectedType"
                                    class="select w-full focus:outline-none border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full px-2 py-1 pr-6"
                                    wire:loading.class="opacity-50" wire:target="selectedType">
                                    <option value="" selected>All Types</option>
                                    <option value="{{ \App\Models\Product::TYPE_RENT }}">Rent</option>
                                    <option value="{{ \App\Models\Product::TYPE_SALE }}">Sale</option>
                                </select>
                                <div wire:loading wire:target="selectedType"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                    <div
                                        class="animate-spin rounded-full h-3 w-3 border border-orange-normal border-t-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Reset Button -->
                            <button wire:click="resetAllFilters" wire:loading.class="opacity-50 cursor-not-allowed"
                                wire:target="resetAllFilters"
                                class="p-2 text-gray-700 focus:outline-none transition-transform duration-300 ease-in-out hover:text-orange-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    wire:loading.class="animate-spin" wire:target="resetAllFilters">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </button>
                        </div>

                        {{-- Funnel button (mobile only) --}}
                        <x-button variant="secondary" class="!rounded-full lg:hidden flex-shrink-0"
                            x-on:click="filterOpen = !filterOpen">
                            <span x-show="filterOpen && window.innerWidth < 1024" x-cloak>
                                <flux:icon name="magnifying-glass" />
                            </span>
                            <span x-show="!filterOpen || window.innerWidth >= 1024">
                                <flux:icon name="funnel" />
                            </span>


                        </x-button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Loading Overlay for Page Navigation -->
    <div wire:loading.delay.longer wire:target="searchTerm,selectedRooms,selectedCity,selectedType,resetAllFilters"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-[60] flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center gap-3">
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-orange-normal border-t-transparent"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for filter updates from home component
            window.addEventListener('updateHomeFilters', function(event) {
                // This will sync any additional filter updates if needed
                console.log('Filters updated:', event.detail);
            });
        });
    </script>
</div>
