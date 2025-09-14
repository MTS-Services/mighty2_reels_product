<div x-data="{ openProfileDropdown: @entangle('openProfileDropdown').live, sidebarOpen: @entangle('sidebarOpen').live, filterOpen: @entangle('filterOpen').live }">
    <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg">
        <div class="container max-w-[1200px] mx-auto py-3 px-4 md:px-6 lg:px-8">
            <nav class="flex items-center justify-between gap-16">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                    <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>
                <div class="items-center gap-9 flex-1 hidden lg:flex justify-between">
                    <x-button href="{{ route('favorites') }}" variant="secondary"
                        class="!rounded-full {{ request()->routeIs('favorites') ? 'bg-orange-500 hover:bg-orange-600 text-white' : '' }}">
                        <flux:icon name="heart" class="fill-orange-normal" /> Saved Reels
                    </x-button>

                    @if (!Route::is('profile'))
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Rooms Select -->
                            <select wire:model.live="selectedRoom"
                                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2">
                                <option value="">Rooms</option>
                                @for ($room = 1; $room <= $maxRooms; $room++)
                                    <option value="{{ $room }}">
                                        {{ $room . ' ' . ($room > 1 ? 'Rooms' : 'Room') }}
                                    </option>
                                @endfor
                            </select>

                            <!-- City Select -->
                            <select wire:model.live="selectedCity"
                                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2">
                                <option value="">City</option>
                                @foreach ($cities as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>

                            <!-- Type Select -->
                            <select wire:model.live="selectedType"
                                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal text-orange-400 bg-orange-normal/10 rounded-full flex-1 px-6 py-2">
                                <option value="">Type</option>
                                <option value="{{ \App\Models\Product::TYPE_RENT }}">Rent</option>
                                <option value="{{ \App\Models\Product::TYPE_SALE }}">Sale</option>
                            </select>
                        </div>
                    @endif
                </div>

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
                <div class="flex items-center gap-4">
                    <x-button variant="secondary" class="!rounded-full !hidden md:!flex lg:!hidden">
                        <flux:icon name="heart" class="fill-orange-normal" />
                    </x-button>

                    <x-button x-on:click="sidebarOpen = true" size="lg" variant="secondary" class="lg:hidden">
                        <flux:icon name="bars-4" />
                    </x-button>
                </div>
            </nav>
        </div>
    </header>

    {{-- Sidebar --}}
    <aside class="fixed inset-0 z-50" x-show="sidebarOpen" x-cloak
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="transform translate-x-full"
        x-transition:enter-end="transform translate-x-0" x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
        @click.outside="sidebarOpen = false">
        <div class="relative w-full h-full">
            <button class="absolute inset-0 bg-orange-light/20 backdrop-blur-lg"
                x-on:click="sidebarOpen = false"></button>
            <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto flex flex-col">
                <div class="flex items-center justify-between px-4 py-3">
                    <a class="inline-flex items-center justify-start gap-4">
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
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'profile' ? 'ring-2 ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="user-circle" /> Profile
                    </x-button>
                    <x-button href="{{ route('favorites') }}" wire:navigate
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'reels' ? 'ring-2 ring-orange-normal!' : '' }}"
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

    {{-- Search Area --}}
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[564px] mt-7 space-y-2 search-area">
        <div class="flex items-center gap-4" x-data="{ searchFocused: false }">
            <form wire:submit.prevent="performSearch" class="flex-1 relative">
                <div class="relative">
                    <input type="search" 
                           placeholder="Search for products..."
                           wire:model.live.debounce.300ms="search"
                           @focus="searchFocused = true"
                           @blur="setTimeout(() => searchFocused = false, 200)"
                           class="w-full px-4 py-2 pr-10 rounded-full outline outline-orange-normal text-orange-400 focus:ring-2 focus:ring-orange-normal transition-all duration-200" />
                    
                    <!-- Search Icon -->
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <flux:icon name="magnifying-glass" class="h-5 w-5 text-orange-400" />
                    </div>
                    
                    <!-- Clear Search Button -->
                    @if($search)
                        <button type="button" 
                                wire:click="clearSearch"
                                class="absolute inset-y-0 right-10 flex items-center pr-2 hover:text-red-500 transition-colors duration-200">
                            <flux:icon name="x-mark" class="h-4 w-4 text-gray-400 hover:text-red-500" />
                        </button>
                    @endif
                </div>
            </form>
            
            <x-button variant="secondary" class="!rounded-full lg:hidden" x-on:click="filterOpen = !filterOpen">
                <flux:icon name="funnel" />
            </x-button>
        </div>

        <!-- Mobile Filters -->
        <div x-show="filterOpen" x-cloak 
             x-transition:enter="transition-all duration-300 ease-in-out"
             x-transition:enter-start="opacity-0 transform -translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition-all duration-300 ease-in-out" 
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2" 
             class="space-y-2 lg:hidden">
            
            <!-- Mobile Room Filter -->
            <select wire:model.live="selectedRoom"
                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal rounded-full w-full bg-orange-light/20 text-orange-400">
                <option value="">All Rooms</option>
                @for ($room = 1; $room <= $maxRooms; $room++)
                    <option value="{{ $room }}">
                        {{ $room . ' ' . ($room > 1 ? 'Rooms' : 'Room') }}
                    </option>
                @endfor
            </select>
            
            <!-- Mobile City Filter -->
            <select wire:model.live="selectedCity"
                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal rounded-full w-full bg-orange-light/20 text-orange-400">
                <option value="">All Cities</option>
                @foreach ($cities as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            
            <!-- Mobile Type Filter -->
            <select wire:model.live="selectedType"
                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal rounded-full w-full bg-orange-light/20 text-orange-400">
                <option value="">All Types</option>
                <option value="{{ \App\Models\Product::TYPE_RENT }}">Rent</option>
                <option value="{{ \App\Models\Product::TYPE_SALE }}">Sale</option>
            </select>
            
            <!-- Clear All Filters Button -->
            @if($search || $selectedRoom || $selectedCity || $selectedType)
                <button wire:click="clearSearch" 
                        class="w-full px-4 py-2 text-sm text-red-500 hover:text-red-600 border border-red-200 hover:border-red-300 rounded-full transition-colors duration-200">
                    Clear All Filters
                </button>
            @endif
        </div>

        <!-- Search Results Summary -->
        @if($search || $selectedRoom || $selectedCity || $selectedType)
            <div class="text-sm text-gray-600 px-2" wire:ignore.self>
                <span>Searching for: </span>
                @if($search)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mr-1">
                        "{{ $search }}"
                        <button wire:click="$set('search', '')" class="ml-1 hover:text-orange-600">
                            <flux:icon name="x-mark" class="h-3 w-3" />
                        </button>
                    </span>
                @endif
                @if($selectedRoom)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                        {{ $selectedRoom }} {{ $selectedRoom > 1 ? 'Rooms' : 'Room' }}
                        <button wire:click="$set('selectedRoom', '')" class="ml-1 hover:text-blue-600">
                            <flux:icon name="x-mark" class="h-3 w-3" />
                        </button>
                    </span>
                @endif
                @if($selectedCity)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                        {{ $cities[$selectedCity] ?? 'City' }}
                        <button wire:click="$set('selectedCity', '')" class="ml-1 hover:text-green-600">
                            <flux:icon name="x-mark" class="h-3 w-3" />
                        </button>
                    </span>
                @endif
                @if($selectedType)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-1">
                        {{ $selectedType == \App\Models\Product::TYPE_RENT ? 'Rent' : 'Sale' }}
                        <button wire:click="$set('selectedType', '')" class="ml-1 hover:text-purple-600">
                            <flux:icon name="x-mark" class="h-3 w-3" />
                        </button>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>