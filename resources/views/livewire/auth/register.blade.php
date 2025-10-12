<section x-data="{ step: @entangle('nextStep').live }">
    <form method="POST" wire:submit="register" class="flex flex-col gap-6">

        <template x-if="step === false">
            <div>
                <div class="flex items-center justify-center lg:p-12">
                    <div class="bg-white p-10 shadow-lg auto w-[800px]">
                        <h2 class="text-2xl font-semibold text-gray-800">Create Your Account</h2>
                        <p class="text-gray-600 mt-2 mb-6">Let's get started with your journey.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-900">First
                                    Name</label>
                                <input type="text" id="firstName" wire:model="first_name"
                                    class="text-gray-400 mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                    value="Jenny">
                                @error('first_name')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-900">Last Name</label>
                                <input type="text" id="lastName" wire:model="last_name"
                                    class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 text-gray-400"
                                    value="Wilson">
                                @error('last_name')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                                <input type="email" id="email" wire:model="email"
                                    class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 text-gray-400"
                                    value="alma.lawson@example.com">
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-900">Phone
                                    Number</label>
                                <input type="tel" id="phone" wire:model="phone"
                                    class="text-gray-400 mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                    value="0412 345 678">
                                @error('phone')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Account password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 ">Password</label>
                                    <input type="password" id="password" wire:model="password"
                                        class="text-gray-400 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                        value="********">
                                    @error('password')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm
                                        Password</label>
                                    <input type="password" id="confirmPassword" wire:model="password_confirmation"
                                        class="text-gray-400 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3"
                                        value="********">
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-button variant="primary" wire:click.prevent="next" class="w-full mt-3 py-4 px-4">
                            <span>Continue </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 " fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </x-button>
                        <div class="text-center mt-4 text-sm text-gray-500">
                            or <a href="{{ route('login') }}" wire:navigate
                                class="text-orange-500 hover:text-orange-600">Log in</a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="step === true">
            <div>
                <div class="flex items-center justify-center lg:p-12">
                    <div class="bg-white p-10 shadow-lg auto w-[800px]">
                        <div class="flex justify-between align-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Build Your Profile</h2>
                                <p class="text-gray-600 mt-2 mb-6">Tell us more about yourself..</p>
                            </div>
                            <div>
                                <x-button @click="step = false" variant="secondary" icon="home">Back</x-button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label for="state" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-1 focus:outline-none text-gray-950"
                                    wire:model="gender" id="gender">
                                    @foreach (App\Models\User::GENDERS as $gender => $label)
                                        <option value="{{ $gender }}"
                                            {{ old('gender') == $gender ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
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
                                @error('date_of_birth')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="relative">
                                <label for="street" class="block text-sm font-medium text-gray-700">Street</label>
                                <input type="text" id="street" wire:model="street" name="street"
                                    class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                    value="Connecticut 35624">
                                @error('street')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="relative">
                                <label for="surburb" class="block text-sm font-medium text-gray-700">Suburb</label>
                                <input type="text" id="surburb" wire:model="surburb" name="surburb"
                                    class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                    value="2464 Royal Ln. Mesa, ">
                                @error('surburb')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <label for="postcode" class="block text-sm font-medium text-gray-700">
                                        Postcode</label>
                                    <input type="text" id="postcode" wire:model="postcode" name="postcode"
                                        class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                        value="567889">
                                    @error('postcode')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="state" wire:model="state" name="state"
                                        class="text-gray-500 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-3 pr-10"
                                        value="Connecticut 35624">
                                    @error('state')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-button variant="primary" type="submit" class="w-full mt-3 py-4 px-4">
                            <span>Finish </span>
                        </x-button>
                    </div>
                </div>
            </div>
        </template>
    </form>
</section>
