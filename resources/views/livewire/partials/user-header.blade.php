<div x-data="{ openProfileDropdown: @entangle('openProfileDropdown').live, sidebarOpen: @entangle('sidebarOpen').live, filterOpen: @entangle('filterOpen').live }">
    <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg">
        <div class="container max-w-[1200px] mx-auto py-3 px-4 md:px-6 lg:px-8">
            <nav class="flex items-center justify-between gap-16">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                    <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>
                <div class="items-center gap-9 flex-1 hidden lg:flex">
                    <x-button variant="secondary" class="!rounded-full">
                        <flux:icon name="heart" class="fill-orange-normal" /> Saved Reels
                    </x-button>

                    <select
                        class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal rounded-full select-lg flex-1 bg-orange-light/20">
                        <option value="">Location</option>
                        <option value="">1246, New York</option>
                        <option value="">1346, New York</option>
                        <option value="">1446, New York</option>
                        <option value="">1546, New York</option>
                    </select>
                </div>

                <div class="hidden lg:flex items-center gap-4 relative py-2"
                    x-on:click.away="openProfileDropdown = false">

                    <div class="flex items-center gap-4 cursor-pointer"
                        x-on:click="openProfileDropdown = !openProfileDropdown">
                        <img src="{{ asset('assets/images/profile.jpg') }}" alt="Name"
                            class="w-11 h-11 rounded-full object-cover">
                        <div class="flex flex-col">
                            <span class="text-lg font-medium text-black">Admin Name</span>
                            <span class="text-xs text-black">example@gmail.com</span>
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
                            <x-button class="w-full" variant="secondary" size="sm">
                                <flux:icon name="user-circle" /> Profile
                            </x-button>
                            <flux:separator variant="subtle" />
                            <x-button class="w-full" variant="secondary" size="sm" wire:click="logout">
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
                    <x-button
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'profile' ? 'ring-2 ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="user-circle" /> Profile
                    </x-button>
                    <x-button
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'reels' ? 'ring-2 ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="heart" class="fill-orange-normal text-orange-normal" /> Saved Reels
                    </x-button>
                    <flux:spacer />
                    <flux:separator variant="subtle" class="my-2" />
                    <x-button class="w-full mb-3" variant="secondary" size="lg">
                        <flux:icon name="power" /> Logout
                    </x-button>
                </div>
            </div>
        </div>
    </aside>

    {{-- Search Area --}}
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[564px] mt-7 space-y-2">
        <div class="flex items-center gap-4">
            <form class="flex-1">
                <input type="search" placeholder="Search for products..."
                    class="w-full px-4 py-2 rounded-full outline outline-orange-normal focus:ring-2 focus:ring-orange-normal" />
            </form>
            <x-button variant="secondary" class="!rounded-full lg:hidden" x-on:click="filterOpen = !filterOpen">
                <flux:icon name="funnel" />
            </x-button>
        </div>

        <div x-show="filterOpen" x-cloak x-transition:enter="transition-all duration-300 ease-in-out"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all duration-300 ease-in-out" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="space-y-2">
            <select
                class="select focus:outline-none focus-within:ring-0 focus-within:ring-offset-0 border-orange-normal rounded-full w-full bg-orange-light/20">
                <option value="">Location</option>
                <option value="">1246, New York</option>
                <option value="">1346, New York</option>
                <option value="">1446, New York</option>
                <option value="">1546, New York</option>
            </select>
        </div>
    </div>
</div>
