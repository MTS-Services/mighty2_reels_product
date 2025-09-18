<div>
    <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg" x-data="{ sidebarOpen: @entangle('sidebarOpen').live }">
        <div class="container max-w-[1200px] mx-auto py-3 px-4 md:px-6 lg:px-8">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                    <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>
                <div class="items-center justify-end gap-6 w-auto hidden lg:flex">
                    <x-button href="{{ route('admin.area') }}" variant="secondary" wire:navigate
                        class="{{ $pageSlug != 'reels' ? 'text-black' : '' }}" size="lg" :linkText="$pageSlug != 'area'">
                        <flux:icon name="map" /> Area
                    </x-button>
                    <x-button href="{{ route('admin.product.reels') }}" wire:navigate variant="secondary"
                        class="{{ $pageSlug != 'reels' ? 'text-black' : '' }}" size="lg" :linkText="$pageSlug != 'product-reel'">
                        <flux:icon name="film" /> Reel
                    </x-button>
                    <x-button href="{{ route('admin.products') }}" wire:navigate variant="secondary"
                        class="{{ $pageSlug != 'product' ? 'text-black' : '' }}" size="lg" :linkText="$pageSlug != 'product'">
                        <flux:icon name="box" /> Product
                    </x-button>
                    <flux:separator vertical />

                    <div class="flex items-center gap-4 relative py-2" x-data="{ openProfileDropdown: @entangle('openProfileDropdown').live }"
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
                                    href="{{ route('profile') }}" wire:navigate="">
                                    <flux:icon name="user-circle" /> Profile
                                </x-button>
                                <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                    href="{{ route('admin.products') }}" wire:navigate>
                                    <flux:icon name="box" /> Product
                                </x-button>
                                <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                    href="{{ route('admin.product.reels') }}" wire:navigate>
                                    <flux:icon name="film" /> Reel
                                </x-button>
                                <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                    href="{{ route('admin.area') }}" wire:navigate>
                                    <flux:icon name="map" /> Area
                                </x-button>

                                <x-button class="w-full !justify-start" variant="secondary" size="sm"
                                    wire:click="logout">
                                    <flux:icon name="power" /> Logout
                                </x-button>
                            </div>
                        </div>
                    </div>

                </div>

                <x-button x-on:click="sidebarOpen = true" size="sm" variant="secondary" class="lg:hidden">
                    <flux:icon name="bars-4" />
                </x-button>
            </nav>
        </div>
    </header>
    <aside class="fixed inset-0 z-50" x-data="{ sidebarOpen: @entangle('sidebarOpen').live }" x-show="sidebarOpen" x-cloak
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="transform translate-x-full"
        x-transition:enter-end="transform translate-x-0" x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
        @click.outside="sidebarOpen = false">
        <div class="relative w-full h-full">
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
                    <x-button href="{{ route('admin.area') }}" wire:navigate
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'area' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="map" /> Area
                    </x-button>
                    <x-button href="{{ route('admin.product.reels') }}" wire:navigate
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'product-reel' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="film" /> Reels
                    </x-button>
                    <x-button href="{{ route('admin.products') }}" wire:navigate
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'product' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="box" /> Product
                    </x-button>
                    <x-button href="{{ route('profile') }}" wire:navigate
                        class="text-black !py-1 w-full justify-start {{ $pageSlug == 'profile' ? 'ring-2 bg-orange-normal! text-white! ring-orange-normal!' : '' }}"
                        size="lg" linkText>
                        <flux:icon name="user-circle" /> Profile
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
</div>
