<section class="flex items-center justify-center py-8 px-4 h-full">
    @push('styles')
        <style>
            .search-area {
                display: none;
            }
        </style>
    @endpush

    <div class="w-full max-w-6xl bg-[#FFFFFF]">

        <div class="bg-white shadow-lg rounded-md w-full p-6 sm:p-8">
            <!-- Profile Image Upload -->


            <!-- Form -->
            <form wire:submit.prevent="updateProfile" class="space-y-6">

                <div class="card">
                    <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div x-data="{ photoName: null, photoPreview: null }" class="flex justify-center mb-6 group">
                            <div class="relative">
                                <img x-show="!photoPreview"
                                    class="w-72 h-72 rounded-full object-cover border-2 border-gray-200"
                                    src="{{ auth()->user()->profile_photo_url ?? 'https://i.pravatar.cc/150?img=3' }}"
                                    alt="Profile">

                                <template x-if="photoPreview">
                                    <img :src="photoPreview"
                                        class="w-72 h-72 rounded-full object-cover border-2 border-gray-200">
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
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" wire:model.defer="first_name"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" wire:model.defer="last_name"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model.defer="email"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" wire:model.defer="phone"
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select wire:model.defer="gender"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select Gender</option>
                                @foreach (['male', 'female', 'other'] as $genderOption)
                                    <option value="{{ $genderOption }}">{{ ucfirst($genderOption) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" wire:model.defer="date_of_birth"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Street</label>
                            <select wire:model.defer="street"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select Street</option>
                                @foreach ($streets as $streetOption)
                                    <option value="{{ $streetOption }}">{{ $streetOption }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <select wire:model.defer="location"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select Location</option>
                                @foreach ($locations as $locationOption)
                                    <option value="{{ $locationOption }}">{{ $locationOption }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Post Code</label>
                            <select wire:model.defer="post_code"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select Post Code</option>
                                @foreach ($postCodes as $code)
                                    <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">State</label>
                            <select wire:model.defer="state"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                <option value="">Select State</option>
                                @foreach ($states as $stateOption)
                                    <option value="{{ $stateOption }}">{{ $stateOption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="card">
                    <h2 class="text-lg font-semibold mb-4">Change Password</h2>
                    <div class="rounded-md grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" wire:model.defer="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" wire:model.defer="password_confirmation"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>
                    </div>
                </div>



                <!-- Save Button -->
                <x-button type="submit" class="w-full">
                    Save
                </x-button>

            </form>
        </div>
    </div>
</section>
