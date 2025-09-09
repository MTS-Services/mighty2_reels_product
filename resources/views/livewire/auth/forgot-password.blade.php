<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center" 
             style="background-image: url('{{ asset('assets/images/Forget_Password.png') }}')">
            <!-- Placeholder image -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">

                <!-- Header -->
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Forgot Password
                    </h2>
                    <p class="mt-4 text-sm text-gray-500">
                        Enter your email address and we'll send you a 6-digit OTP to reset your password.
                    </p>
                </div>

                <!-- Display success message -->
                @if (session()->has('status'))
                    <div class="text-green-600 text-sm mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="mt-8 space-y-6" wire:submit.prevent="sendPasswordResetOtp">

                    <div>
                        <label for="email-address" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-1">
                            <input id="email-address" type="email" wire:model="email" required
                                class="text-gray-900 appearance-none relative block w-full px-5 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your registered email address">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            We'll send a 6-digit OTP to this email address.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span wire:loading.remove wire:target="sendPasswordResetOtp">Send OTP</span>
                            <span wire:loading wire:target="sendPasswordResetOtp">Sending OTP...</span>
                        </x-button>
                    </div>
                </form>

                <!-- Cancel Button -->
                <div>
                    <button type="button"
                        onclick="window.location='{{ route('login') }}'"
                        class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                        Back to Login
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>