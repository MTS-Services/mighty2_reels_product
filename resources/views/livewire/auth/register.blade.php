<section x-data="{ step: 1 }">
    <div class="items-center justify-center flex lg:p-6">
        <div class="flex items-center justify-center ">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
        </div>
        <span class="text-orange-500 font-medium text-xl text-center">REEL SHOP</span>
    </div>

    <template x-if="step === 1">
        <div>
            <div class="flex items-center justify-center lg:p-12">
                <div class="bg-white p-10 shadow-lg auto w-[800px]">
                    <h2 class="text-2xl font-semibold text-gray-800">Create Your Account</h2>
                    <p class="text-gray-600 mt-2 mb-6">Let's get started with your journey.</p>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-900">First
                                    Name</label>
                                <input type="text" id="firstName" name="firstName"
                                    class="text-gray-400 mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                    value="Jenny">
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-900">Last Name</label>
                                <input type="text" id="lastName" name="lastName"
                                    class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 text-gray-400"
                                    value="Wilson">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                                <input type="email" id="email" name="email"
                                    class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 text-gray-400"
                                    value="alma.lawson@example.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-900">Phone
                                    Number</label>
                                <input type="tel" id="phone" name="phone"
                                    class="text-gray-400 mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                    value="0412 345 678">
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Account password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 ">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="text-gray-400 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                        value="********">
                                </div>
                                <div>
                                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm
                                        Password</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword"
                                        class="text-gray-400 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                        value="********">
                                </div>
                            </div>
                        </div>
                        <x-button variant="primary" @click="step = 2" class="w-full mt-3 py-4 px-4">
                            <span>Continue </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 " fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </x-button>
                        <div class="text-center mt-4 text-sm text-gray-500">
                            or <a href="{{ route('login') }}" wire:navigate
                                class="text-orange-500 hover:text-orange-600">Log in</a> to manage your
                            lessons and bookings.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-if="step === 2">
        <div>
            <div class="flex items-center justify-center lg:p-12">
                <div class="bg-white p-10 shadow-lg auto w-[800px]">
                    <div class="flex justify-between align-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Build Your Profile</h2>
                            <p class="text-gray-600 mt-2 mb-6">Tell us more about yourself..</p>
                        </div>
                        <div>
                            <x-button @click="step = 1" variant="secondary" icon="home">Back</x-button>
                        </div>
                    </div>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label for="state" class="block text-sm font-medium text-gray-700">Gender</label>
                                <input type="text" id="state" name="state"
                                    class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                    value="Male ">
                                <flux:icon name="chevron-down"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none mt-3" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900">Date of Birth</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <input type="text" placeholder="DD" maxlength="2"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-1 focus:outline-none text-center text-gray-950">
                                    <input type="text" placeholder="MM" maxlength="2"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-1 focus:outline-none text-center text-gray-950">
                                    <input type="text" placeholder="YYYY" maxlength="4"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-1 focus:outline-none text-center text-gray-950">
                                </div>
                            </div>
                            <div class="relative">
                                <label for="state" class="block text-sm font-medium text-gray-700">Street</label>
                                <input type="text" id="state" name="state"
                                    class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                    value="6391 Elgin St. Celina, Delaware 10299, ">
                                <flux:icon name="chevron-down"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none mt-3" />
                            </div>
                            <div class="relative">
                                <label for="state" class="block text-sm font-medium text-gray-700">Suburb</label>
                                <input type="text" id="state" name="state"
                                    class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                    value="2464 Royal Ln. Mesa, ">
                                <flux:icon name="chevron-down"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none mt-3" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <label for="state" class="block text-sm font-medium text-gray-700">
                                        Postcode</label>
                                    <input type="text" id="state" name="state"
                                        class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                        value="567889">
                                    <flux:icon name="chevron-down"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none mt-3" />
                                </div>
                                <div class="relative">
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="state" name="state"
                                        class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                        value="Connecticut 35624">
                                    <flux:icon name="chevron-down"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none mt-3" />
                                </div>
                            </div>
                        </div>
                        <x-button variant="primary" class="w-full mt-3 py-4 px-4">
                            <span>Finish </span>
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</section>
