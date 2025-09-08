<div class="sticky top-0 z-50 bg-white pb-0.5">
    <header class="bg-orange-light/20 shadow sticky top-0 right-0 z-50 backdrop-blur-lg" x-data="{ sidebarOpen: @entangle('sidebarOpen').live }">
        <div class="container max-w-[1200px] mx-auto py-3 px-4 md:px-6 lg:px-8">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center justify-start gap-4">
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-9 h-9 object-cover">
                    <span class="text-[26px] text-orange-normal capitalize"> {{ site_name() }}</span>
                </a>
                <div class="items-center justify-end gap-6 w-auto hidden lg:flex">
                    @if (Route::is('login'))
                        <x-button route="register" size="md">
                            <flux:icon name="user-plus" /> Register
                        </x-button>
                    @elseif (Route::is('register'))
                        <x-button variant="secondary" route="login" size="md">
                            <flux:icon name="log-in" /> Login
                        </x-button>
                    @else
                        <x-button variant="secondary" route="login" size="md">
                            <flux:icon name="log-in" /> Login
                        </x-button>
                        <x-button route="register" size="md">
                            <flux:icon name="user-plus" /> Register
                        </x-button>
                    @endif
                </div>

                <x-button x-on:click="sidebarOpen = true" size="lg" variant="secondary" class="lg:hidden">
                    <flux:icon name="bars-4" />
                </x-button>
            </nav>
        </div>
    </header>
    {{-- Search Area --}}
   @if (Route::is('home') || Route::is('product'))
        <div class="container mx-auto px-4 z-50  md:px-6 lg:px-8 max-w-lg  my-5 space-y-2">
            <div class="flex items-center gap-4">
                <form class="flex-1">
                    <input type="search" placeholder="Search for products..."
                        class="w-full px-4 py-2 text-gray-800 rounded-full outline outline-orange-normal focus:ring-2 focus:ring-orange-normal" />
                </form>
                <x-button variant="secondary" class="!rounded-full lg:hidden" x-on:click="filterOpen = !filterOpen">
                    <flux:icon name="funnel" />
                </x-button>
            </div>
        </div>
    @endif
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
                    @if (Route::is('login'))
                        <x-button route="register" size="md" class="w-full">
                            <flux:icon name="user-plus" /> Register
                        </x-button>
                    @elseif (Route::is('register'))
                        <x-button variant="secondary" route="login" size="md" class="w-full">
                            <flux:icon name="log-in" /> Login
                        </x-button>
                    @else
                        <x-button variant="secondary" route="login" size="md" class="w-full">
                            <flux:icon name="log-in" /> Login
                        </x-button>
                        <x-button route="register" size="md" class="w-full">
                            <flux:icon name="user-plus" /> Register
                        </x-button>
                    @endif

                </div>
            </div>
        </div>
    </aside>
</div>
