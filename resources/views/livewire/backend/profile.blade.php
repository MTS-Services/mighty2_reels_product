<section class="flex items-center relative justify-center py-8 px-4 h-full" x-data="{ sidebarOpen: false }">
    @push('styles')
        <style>
            .search-area {
                display: none;
            }
        </style>
    @endpush

    <div class="sm:hidden absolute top-4 left-0 right-0 z-40 flex items-center px-4">
        <!-- Logo centered -->
        <a href="{{ route('home') }}" wire:navigate
            class="absolute left-1/2 transform -translate-x-1/2 bg-[#FFE8D9] rounded-md px-2 inline-flex animate-fade-in-up items-center gap-4">
            <span class="text-[20px] text-orange-normal capitalize">{{ site_name() }}</span>
        </a>

        <!-- Hamburger button on the right -->
        <div class="ml-auto animate-fade-in-up">
            <x-button x-on:click="sidebarOpen = true" size="sm" variant="secondary" class="lg:hidden">
                <flux:icon name="bars-4" />
            </x-button>
        </div>
    </div>
    {{-- Sidebar start --}}
    <aside class="fixed inset-0 z-[999999]" x-show="sidebarOpen" x-cloak
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
                            <flux:icon name="heart" class="fill-orange-normal text-orange-normal" /> Saved
                            Reels
                        </x-button>

                        <flux:spacer />
                        <flux:separator variant="subtle" class="my-2" />
                        <x-button class="w-full mb-3" variant="secondary" size="lg" wire:click="logout">
                            <flux:icon name="power" /> Logout
                        </x-button>
                    @else
                        @if (Route::is('login'))
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="register"
                                wire:navigate>
                                <flux:icon name="user-plus" /> Register
                            </x-button>
                        @elseif (Route::is('register'))
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="login"
                                wire:navigate>
                                <flux:icon name="log-in" /> Login
                            </x-button>
                        @else
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="login"
                                wire:navigate>
                                <flux:icon name="log-in" /> Login
                            </x-button>
                            <x-button class="w-full !justify-start" variant="secondary" size="lg" route="register"
                                wire:navigate>
                                <flux:icon name="user-plus" /> Register
                            </x-button>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </aside>
    {{-- Sidebar  end --}}
    <div class="w-full max-w-6xl bg-[#FFFFFF]">

        <div class="bg-white shadow-lg rounded-md w-full p-6 sm:p-8">
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Profile Image Upload -->


            <!-- Form -->
            <form wire:submit.prevent="updateProfile" class="space-y-6">

                <div class="card">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div x-data="{ photoName: null, photoPreview: null }" class="flex justify-center mb-6 group">
                            <div class="relative">
                                <img x-show="!photoPreview"
                                    class="w-72 h-72 rounded-full object-contain border-2 border-gray-200"
                                    src="{{ storage_url(auth()->user()->photo) ?? 'https://i.pravatar.cc/150?img=3' }}"
                                    alt="Profile">

                                <template x-if="photoPreview">
                                    <img :src="photoPreview"
                                        class="w-72 h-72 rounded-full object-contain border-2 border-gray-200">
                                </template>

                                <input type="file" class="hidden" x-ref="photo" wire:model="photo"
                                    accept="image/*"
                                    x-on:change="
                photoName = $refs.photo.files[0].name;
                const reader = new FileReader();
                reader.onload = (e) => { photoPreview = e.target.result };
                reader.readAsDataURL($refs.photo.files[0]);">

                                <button type="button" x-on:click.prevent="$refs.photo.click()"
                                    class="absolute w-full h-full mx-auto left-0 top-0  rounded-full shadow-md transition text-orange-500 text-center hover:text-orange-500 group-hover:bg-black/50">
                                    <flux:icon name="camera"
                                        class="w-8 h-8 mx-auto opacity-0 group-hover:opacity-100  " />
                                </button>
                            </div>
                        </div>
                        <div class="rounded-md grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 ">First Name</label>
                                <input type="text" wire:model.defer="first_name"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                @error('first_name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 ">Last Name</label>
                                <input type="text" wire:model.defer="last_name"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 ">Email</label>
                                <input type="email" wire:model.defer="email"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 ">Phone Number</label>
                                <input type="text" wire:model.defer="phone"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                @error('phone')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Gender</label>
                            <select wire:model.defer="gender"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select Gender</option>
                                @foreach (App\Models\User::GENDERS as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Date of Birth</label>
                            <input type="date" wire:model.defer="date_of_birth"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('date_of_birth')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Street</label>
                            <input type="text" wire:model.defer="street"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('street')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Suburb</label>
                            <input type="text" wire:model.defer="suburb"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('suburb')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Post Code</label>
                            <input type="text" wire:model.defer="postcode"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('postcode')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">State</label>
                            <input type="text" wire:model.defer="state"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('state')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="card">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900">Change Password</h2>
                    <div class="rounded-md grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">New Password</label>
                            <input type="password" wire:model.defer="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 ">Confirm Password</label>
                            <input type="password" wire:model.defer="password_confirmation"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>
                    </div>
                </div>



                <!-- Save Button -->
                <x-button type="submit" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove> Update</span>
                    <span wire:loading>Updating...</span>
                </x-button>

            </form>
        </div>
    </div>
</section>
