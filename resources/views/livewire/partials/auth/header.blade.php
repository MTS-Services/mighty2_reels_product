<div>
    <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg" x-data="{ sidebarOpen: @entangle('sidebarOpen').live }">
        <div class="container max-w-[1200px] mx-auto py-3">
            <nav class="flex items-center justify-between">
                <a class="inline-flex items-center justify-start gap-4">
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                    <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>
                <div class="items-center justify-end gap-6 w-auto hidden lg:flex">
                    <x-button class="text-black" size="lg" linkText>
                        <flux:icon name="film" /> Reels
                    </x-button>
                    <x-button variant="secondary" class="" size="lg">
                        <flux:icon name="box" /> Product
                    </x-button>
                    <x-button class="text-black" size="lg" linkText>
                        <flux:icon name="user-circle" /> Profile
                    </x-button>
                </div>

                <x-button x-on:click="sidebarOpen = true" size="lg" variant="secondary">
                    <flux:icon name="bars-4" />
                </x-button>

            </nav>
        </div>
    </header>
    <aside class="absolute inset-0 z-50" x-data="{ sidebarOpen: @entangle('sidebarOpen').live }" x-show="sidebarOpen" x-cloak>
        <div class="relative w-full h-full">
            <button class="absolute top-0 left-0 w-full h-full bg-orange-light/20 backdrop-blur-lg"></button>
            <div class="relative w-full h-full bg-white shadow-lg max-w-96 ml-auto">
                <div class="flex items-center justify-between px-4 py-3">
                    <a class="inline-flex items-center justify-start gap-4">
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                        <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                    </a>
                    <x-button x-on:click="sidebarOpen = false" size="lg" variant="secondary">
                        <flux:icon name="x-mark" />
                    </x-button>
                </div>
                <div class="px-4 flex flex-col gap-4">
                    <x-button class="text-black" size="lg" linkText>
                        <flux:icon name="film" /> Reels
                    </x-button>
                    <x-button variant="secondary" class="" size="lg">
                        <flux:icon name="box" /> Product
                    </x-button>
                    <x-button class="text-black" size="lg" linkText>
                        <flux:icon name="user-circle" /> Profile
                    </x-button>
                </div>
            </div>
        </div>
    </aside>
</div>
