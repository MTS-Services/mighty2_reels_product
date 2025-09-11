<section class=" flex items-center justify-center py-8 px-4 h-full ">

    @push('styles')
        <style>
            .search-area {
                display: none;
            }
        </style>
    @endpush

    <div class="w-full max-w-6xl bg-[#FFFFFF]">

        <!-- Card -->
        <div class="bg-white shadow-lg rounded-md w-full p-6 sm:p-8">
            <h2 class="text-center text-2xl font-semibold text-gray-900 mb-6">Edit your Account</h2>


            <!-- Profile Image Upload -->
            <div x-data="{ photoName: null, photoPreview: null }" class="flex justify-center mb-6">
                <div class="relative">
                    <!-- Preview Image -->
                    <img x-show="!photoPreview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200"
                        src="https://i.pravatar.cc/150?img=3" alt="Profile">
                    <!-- New Image eview -->
                    <template x-if="photoPreview">
                        <img :src="photoPreview"
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    </template>
                    <!-- Hidden File Input -->
                    <input type="file" class="hidden" x-ref="photo"
                        @change="
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photoPreview = e.target.result; };
                    reader.readAsDataURL($refs.photo.files[0]);">
                    <!-- Camera Icon Overlay -->
                    <button type="button" @click.prevent="$refs.photo.click()"
                        class="absolute bottom-1 right-1   text-white p-2 rounded-full shadow-md transition">
                        <!-- Camera Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7h2l1-2h12l1 2h2a2 2 0 012 2v9a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 012-2z" />
                            <circle cx="12" cy="13" r="3" />
                        </svg>
                    </button>
                </div>
            </div>


            <!-- Form -->
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" placeholder="Jenny"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" placeholder="Wilson"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" placeholder="alma.lawson@example.com"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" placeholder="0412 345 678"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                </div>

                <!-- Password Section -->
                <div class=" rounded-md">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" placeholder="******"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" placeholder="******"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Birth </label>
                        <input type="date" placeholder="Wilson"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Street</label>
                        <select
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="">Select Address</option>
                            <option value="">6391 Elgin St. Celina, Delaware 10299</option>
                            <option value="">6391 Elgin St. Celina, Delaware 10299</option>
                            <option value="">6391 Elgin St. Celina, Delaware 10299</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="">Select Loction</option>
                            <option value="">2464 Royal Ln. Mesa</option>
                            <option value="">2464 Royal Ln. Mesa</option>
                            <option value="">2464 Royal Ln. Mesa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Post Code</label>
                        <select
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="">Select Post Code</option>
                            <option value="">34545</option>
                            <option value="">342523</option>
                            <option value="">34252345</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start</label>
                        <select
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-[#F6F5F5] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="">Select State</option>
                            <option value="">Connecticut 35624</option>
                            <option value="">Connecticut 35624</option>
                            <option value="">Connecticut 35624</option>
                        </select>
                    </div>
                </div>
                <!-- Save Button -->
                <x-button class="w-full" type="submit" size="sm" wire:click="logout">
                    Save
                </x-button>
            </form>
        </div>
    </div>
</section>
