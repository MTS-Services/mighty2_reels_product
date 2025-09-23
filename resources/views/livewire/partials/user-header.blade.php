<div class="sticky top-0 left-0 z-50">
    <div x-data="{
        openProfileDropdown: @entangle('openProfileDropdown').live,
        sidebarOpen: @entangle('sidebarOpen').live,
        filterOpen: @entangle('filterOpen').live
    }" class="w-full">
        <header class="bg-orange-light/20 shadow backdrop-blur-lg">
            <div class="container max-w-[1200px] mx-auto py-0 sm:py-3 px-4 md:px-6 lg:px-8">
                <nav class=" items-center hidden sm:flex justify-between gap-4">
                    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                        <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                    </a>

                    {{-- Desktop Navigation --}}
                    <div class="items-center gap-9 w-[750px] -mr-10 hidden lg:flex justify-between">
                        @if ($pageSlug == 'home' || $pageSlug == 'favorites' || $pageSlug == 'product')
                            <x-button href="{{ route('favorites') }}" variant="secondary" class="!rounded-full">
                                <flux:icon name="heart" class="fill-orange-normal" /> Saved Reels
                            </x-button>
                        @endif

                        {{-- Filter Button for Home Page --}}
                        @if ($pageSlug == 'home')
                            <div class="flex items-center gap-4 justify-end">
                                <button wire:click="openSearchModal"
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-normal/10 text-orange-600 hover:bg-orange-normal/20 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61z" />
                                    </svg>
                                </button>
                                <button wire:click="openFilterModal" variant="secondary"
                                    class="bg-orange-normal/10 text-orange-600 hover:bg-orange-normal/20 p-2! rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                                    </svg>
                                </button>
                                @if (!empty($searchTerm) || !empty($selectedRooms) || !empty($selectedCity) || !empty($selectedType))
                                    <button wire:click="resetAllFilters"
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
                        <div class="hidden lg:flex items-center gap-4 relative py-2 {{ $pageSlug == 'Favorites' || $pageSlug == 'profile' ? 'ml-auto' : '' }}"
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
                                        wire:click="logout">
                                        <flux:icon name="power" /> Logout
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="px-4 hidden lg:block flex {{ Route::is('login') || Route::is('register') || Route::is('product') ? 'ml-auto' : '' }} gap-2 ">
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

                    {{-- Mobile Menu Button - Only show on non-home pages --}}
                    @if ($pageSlug !== 'home')
                        <div class="flex items-center gap-4">
                            <x-button x-on:click="sidebarOpen = true" size="sm" variant="secondary"
                                class="lg:hidden">
                                <flux:icon name="bars-4" />
                            </x-button>
                        </div>
                    @endif
                </nav>
            </div>
        </header>

        {{-- Mobile Floating Action Buttons - Only on Home Page --}}
        @if ($pageSlug == 'home')
            <div class="sm:hidden fixed top-4 left-0 right-0 z-40 flex items-center px-4">
                <!-- Logo centered -->
                <a href="{{ route('home') }}" wire:navigate
                    class="absolute left-1/2 transform -translate-x-1/2 bg-[#FFE8D9] rounded-md px-2 inline-flex items-center gap-4">
                    {{-- <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover"> --}}
                    <span class="text-[20px]  text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>

                <!-- Hamburger button on the right -->
                <div class="ml-auto">
                    <x-button x-on:click="sidebarOpen = true" size="sm" variant="secondary" class="lg:hidden">
                        <flux:icon name="bars-4" />
                    </x-button>
                </div>
            </div>
            <div class="sm:hidden fixed right-4 bottom-1/5 transform -translate-y-1/2 z-40 flex flex-col gap-3">
                {{-- Reset Filters Button (only show when filters are active) --}}
                @if (!empty($searchTerm) || !empty($selectedRooms) || !empty($selectedCity) || !empty($selectedType))
                    <button wire:click="resetAllFilters" wire:loading.class="opacity-50 cursor-not-allowed"
                        class="w-12 h-12 bg-red-500/80 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-red-600/80 transition-all duration-200 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                @endif
                {{-- Filter Button --}}
                <x-button wire:click="openFilterModal" variant="secondary"
                    class="w-12 h-12  backdrop-blur-sm  rounded-full! flex items-center justify-center  transition-all duration-200 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                </x-button>
                {{-- Search Button --}}
                <x-button wire:click="openSearchModal" variant="secondary"
                    class="w-12 h-12  backdrop-blur-sm  rounded-full! flex items-center justify-center  transition-all duration-200 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61z" />
                    </svg>
                </x-button>

                {{-- Profile/Auth Button --}}




            </div>
        @endif

        {{-- Sidebar --}}
        <aside class="fixed inset-0 z-[60]" x-show="sidebarOpen" x-cloak
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
            @click.outside="sidebarOpen = false">
            <div class="relative w-full h-full ">
                <button class="absolute inset-0 bg-orange-light/20 backdrop-blur-lg"
                    x-on:click="sidebarOpen = false"></button>
                <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto flex flex-col">
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
                        @if (Auth::check())
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
        </aside>

        {{-- Remove desktop search area for mobile --}}
        @if ($pageSlug == 'home')
            <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[564px] mt-2 space-y-2 search-area hidden lg:block"
                x-data="{ filterOpen: @entangle('filterOpen').live }">

                <div class="flex items-center gap-4">
                    <div class="flex-1 flex items-center gap-1">
                        <div x-show="filterOpen && window.innerWidth < 1024" x-cloak
                            class="flex-1 flex items-center gap-2"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:leave="transition-all duration-200 ease-in">
                        </div>

                        <x-button variant="secondary" class="!rounded-full lg:hidden flex-shrink-0"
                            x-on:click="filterOpen = !filterOpen">
                            <flux:icon name="funnel" />
                        </x-button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Search Modal - Enhanced for Mobile --}}
    @if ($showSearchModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            wire:click.self="closeSearchModal">
            <div class="bg-white rounded-2xl relative shadow-2xl w-full max-w-xl min-h-[40vh] p-4 flex flex-col space-y-4 lg:max-w-xl"
                wire:click.stop>

                {{-- Modal Header --}}
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Search Properties</h3>
                    <button wire:click="closeSearchModal" class="text-gray-400 hover:text-gray-600">
                        <flux:icon name="x-mark" class="w-6 h-6" />
                    </button>
                </div>

                {{-- Search Input --}}
                <div class="relative">
                    <input type="search" wire:model.live.debounce.500ms="searchTerm"
                        placeholder="Search for products..."
                        class="w-full px-4 py-2  rounded-full outline outline-orange-normal text-orange-600 focus:ring-2 focus:ring-orange-normal bg-orange-normal/10 backdrop-blur-md placeholder:text-orange-400"
                        wire:loading.class="opacity-50" wire:target="searchTerm" />
                </div>

                {{-- Show Recent Searches ONLY if searchTerm is empty --}}
                @if (empty($searchTerm) && !empty($recentSearches))
                    <div class="bg-gray-50 rounded-lg p-3 max-h-48 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-medium text-gray-700">Recent searches:</p>
                            <button wire:click="clearSearches"
                                class="text-xs text-gray-500 hover:text-red-500 font-medium">Clear All</button>
                        </div>
                        <div class="space-y-1">
                            @foreach ($recentSearches as $search)
                                <div
                                    class="flex items-center justify-between  px-3 py-2 hover:bg-white rounded-md group">
                                    <button type="button" class="flex-1 text-gray-800 text-left font-medium"
                                        wire:click="$set('searchTerm', '{{ $search }}')">
                                        <flux:icon name="clock" class="w-4 h-4 inline mr-2 text-gray-400" />
                                        {{ $search }}
                                    </button>
                                    <button type="button" wire:click="removeSearch('{{ $search }}')"
                                        class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity text-black! hover:text-red-500">
                                        <flux:icon name="x-mark" class="w-4 h-4 text-black!" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex justify-between items-center pt-2 mt-auto">
                    <button type="button" wire:click="performSearch"
                        class="bg-orange-500 text-white px-4 py-3 rounded-full hover:bg-orange-600 transition-colors flex items-center gap-2 font-medium">
                        <flux:icon name="check" class="w-5 h-5" />
                        Search
                    </button>

                    <button type="button" wire:click="closeSearchModal"
                        class="px-4 py-3 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors font-medium flex items-center gap-2">
                        <flux:icon name="x-mark" class="w-5 h-5" />
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    @endif

    {{-- Fixed Filter Modal Section - Replace the existing filter modal in your blade template --}}
    @if ($showFilterModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-1"
            wire:click.self="closeFilterModal">
            <div
                class="bg-white rounded-2xl p-6 w-full max-w-md mx-auto shadow-2xl relative overflow-hidden max-h-[85vh] flex flex-col">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center mb-6 flex-shrink-0">
                    <h2 class="text-3xl font-bold text-orange-600">FILTER</h2>
                    <button wire:click="closeFilterModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body: Scrollable --}}
                <div class="flex-1 overflow-y-auto scroll-smooth space-y-6">

                    {{-- Rooms Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Rooms
                            </span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="clearSelectedRooms"
                                class="px-4 py-2 rounded-full border transition-colors {{ empty($selectedRooms) || $selectedRooms == '' ? 'bg-orange-normal text-white border-orange-normal' : 'bg-white text-gray-700 border-gray-300 hover:border-orange-normal' }}">
                                All
                            </button>
                            @for ($room = 1; $room <= 4; $room++)
                                <button wire:click="toggleRoom('{{ $room }}')"
                                    class="px-4 py-2 rounded-full border transition-colors {{ $this->isRoomSelected($room) ? 'bg-orange-normal text-white border-orange-normal' : 'bg-white text-gray-700 border-gray-300 hover:border-orange-normal' }}">
                                    {{ $room }}
                                </button>
                            @endfor
                            <button wire:click="toggleRoom('5+')"
                                class="px-4 py-2 rounded-full border transition-colors {{ $this->isRoomSelected('5+') ? 'bg-orange-normal text-white border-orange-normal' : 'bg-white text-gray-700 border-gray-300 hover:border-orange-normal' }}">
                                5+
                            </button>
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <span class="text-2xl font-black text-orange-600">+</span>Searchable dropdown for cities
                    </p>

                    {{-- FIXED: City Filter with proper wire:model --}}
                    <div class="flex flex-col space-y-2">
                        <select wire:model.live="selectedCity"
                            class="w-full px-4 py-3 text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-600 focus:border-orange-600 bg-white">
                            <option value="">All Cities</option>
                            @foreach ($cities as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FIXED: Property Type Filter with multiple approaches --}}
                    <div>
                        <div class="flex border-2 border-gray-300 rounded-full overflow-hidden">
                            <button wire:click="setForSale"
                                class="flex-1 px-4 py-3 transition-colors text-center font-bold text-lg {{ $selectedType == 'sale' || $selectedType == \App\Models\Product::TYPE_SALE ? 'bg-gray-600 text-white' : 'bg-white text-gray-500' }}">
                                FOR SALE
                            </button>
                            <button wire:click="setForRent"
                                class="flex-1 px-4 py-3 transition-colors text-center font-bold text-lg {{ $selectedType == 'rent' || $selectedType == \App\Models\Product::TYPE_RENT ? 'bg-gray-600 text-white' : 'bg-white text-gray-500' }}">
                                FOR RENT
                            </button>
                        </div>
                      
                    </div>

                </div>

                {{-- Modal Actions --}}
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-4 flex-shrink-0">
                    <button wire:click="applyFilters" wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-orange-600 text-white w-16 h-16 rounded-full flex items-center justify-center transition-colors hover:bg-orange-700">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>

                    @if (!empty($searchTerm) || !empty($selectedRooms) || !empty($selectedCity) || !empty($selectedType))
                        <button wire:click="resetAllFilters" wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-16 h-16 rounded-full flex items-center justify-center border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4l16 16M4 16L16 4"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay for Page Navigation -->
    <div wire:loading.delay.longer wire:target="searchTerm,selectedRooms,selectedCity,selectedType,resetAllFilters"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 flex items-center justify-center">
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
