<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center "
            style="background-image: url('{{ asset('assets/images/register.png') }}">
            <!-- You can replace the placeholder image with your own -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Register Email
                    </h2>
                </div>

                <form class="mt-8 space-y-6" wire:submit="resetPassword" method="POST">
                    <div>
                        <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                            New Password
                        </label>
                        <div class="mt-1">
                            <input id="email-address" wire:model="password" :label="__('Password')" type="password"
                                required
                                class="text-gray-500 appearance-none relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                            Confirm Password
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input id="password_confirmation" wire:model="password_confirmation" type="password"
                                :type="show ? 'text' : 'password'" required
                                class="text-gray-500 appearance-none relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Confirm your password">

                            <!-- Toggle Button -->
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer"
                                @click="show = !show">

                                <!-- Eye Open -->
                                <flux:icon name="eye" class="w-5 h-5 text-gray-500" x-show="!show" />

                                <!-- Eye Slash -->
                                <flux:icon name="eye-slash" class="w-5 h-5 text-gray-500" x-show="show" />
                            </span>
                        </div>

                    </div>

                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span>Submit</span>
                        </x-button>
                    </div>

                    <x-button variant="secondary" class="w-full mt-3">
                        <span>Cancel</span>
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</section>
