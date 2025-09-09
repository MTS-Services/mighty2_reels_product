<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/verify-otp.png') }}')">
            <!-- OTP verification image -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                
                <!-- Header -->
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Verify OTP
                    </h2>
                    <p class="mt-4 text-sm text-gray-500">
                        Enter the 6-digit OTP sent to <span class="font-medium text-gray-700">{{ $email }}</span>
                    </p>
                </div>

                <!-- Display success/error messages -->
                @if (session()->has('status'))
                    <div class="text-green-600 text-sm mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="text-red-600 text-sm mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="mt-8 space-y-6" wire:submit.prevent="verifyOtp">

                    <!-- OTP Input -->
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">
                            6-Digit OTP
                        </label>
                        <div class="mt-1">
                            <input id="otp" type="text" wire:model="otp" required
                                maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                                class="text-black appearance-none relative block w-full px-5 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm text-center text-2xl tracking-widest font-mono"
                                placeholder="000000">
                        </div>
                        @error('otp')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Enter the 6-digit code sent to your email address.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span wire:loading.remove wire:target="verifyOtp">Verify OTP</span>
                            <span wire:loading wire:target="verifyOtp">Verifying...</span>
                        </x-button>
                    </div>

                    <!-- Resend OTP Button -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Didn't receive the OTP?</span>
                        <button type="button" wire:click="resendOtp" 
                            @if($resendCooldown > 0) disabled @endif
                            class="text-sm font-medium text-orange-600 hover:text-orange-500 disabled:text-gray-400 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="resendOtp">
                                @if($resendCooldown > 0)
                                    Resend in {{ $resendCooldown }}s
                                @else
                                    Resend OTP
                                @endif
                            </span>
                            <span wire:loading wire:target="resendOtp">Sending...</span>
                        </button>
                    </div>

                    <!-- Back to Email Button -->
                    <div>
                        <button type="button"
                            onclick="window.location='{{ route('password.request') }}'"
                            class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Use Different Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-refresh remaining time script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Refresh component every second to update remaining time
            setInterval(() => {
                @this.$refresh();
            }, 1000);

            @this.on('start-cooldown', () => {
                const interval = setInterval(() => {
                    @this.call('decrementCooldown');
                    if (@this.resendCooldown <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            });
        });
    </script>
</section>