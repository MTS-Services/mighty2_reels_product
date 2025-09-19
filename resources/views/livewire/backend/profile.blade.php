<section class="flex items-center justify-center py-8 px-4 h-full">
    @push('styles')
        <style>
            .search-area {
                display: none;
            }
        </style>
    @endpush

    <div class="w-full max-w-6xl bg-[#FFFFFF] 
">

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
                                    @change="
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result };
                            reader.readAsDataURL($refs.photo.files[0]);">

                                <button type="button" @click.prevent="$refs.photo.click()"
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
