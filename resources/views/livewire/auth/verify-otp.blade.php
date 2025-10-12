<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/verificason.png') }}')">
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Welcome Back.
                    </h2>
                </div>

                <div class="flex items-center justify-center min-h-screen">
                    <div class="p-8 rounded-lg text-center">
                        <h1 class="text-4xl font-semibold mb-2 text-gray-900">Code Verification</h1>
                        <p class="text-gray-500 mb-6">Enter OTP (One time password) sent to 
                            <span class="font-medium text-black">{{ $email }}</span>
                        </p>

                        <!-- Success/Error Messages -->
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                                 x-data="{ show: true }"
                                 x-show="show"
                                 x-init="setTimeout(() => show = false, 5000)"
                                 x-transition>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
                                 x-data="{ show: true }"
                                 x-show="show"
                                 x-init="setTimeout(() => show = false, 5000)"
                                 x-transition>
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit="verify" class="space-y-6">
                            <div class="flex justify-center space-x-4" x-data="otpInput()" wire:ignore>
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit1"
                                    @input="handleInput($event, 'digit2')"
                                    @keydown.backspace="handleBackspace($event, null)"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit2"
                                    @input="handleInput($event, 'digit3')"
                                    @keydown.backspace="handleBackspace($event, 'digit1')"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit3"
                                    @input="handleInput($event, 'digit4')"
                                    @keydown.backspace="handleBackspace($event, 'digit2')"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit4"
                                    @input="handleInput($event, 'digit5')"
                                    @keydown.backspace="handleBackspace($event, 'digit3')"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit5"
                                    @input="handleInput($event, 'digit6')"
                                    @keydown.backspace="handleBackspace($event, 'digit4')"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-ref="digit6"
                                    @input="handleInput($event, null)"
                                    @keydown.backspace="handleBackspace($event, 'digit5')"
                                    class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" 
                                />

                                <!-- Hidden input for Livewire binding -->
                                <input type="hidden" wire:model="otp" x-ref="hiddenOtp">
                            </div>

                            @error('otp')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror

                            <p class="text-sm text-gray-400 text-right mr-4" 
                               x-data="countdown()" 
                               x-text="timeLeft"
                               @otp-resent.window="resetCountdown()"></p>

                            <x-button type="submit" variant="primary" class="w-full mt-3">
                                <span>Verify Code</span>
                            </x-button>

                            <x-button type="button" wire:click="resendOtp" variant="secondary" class="w-full mt-3">
                                <span>Resend Code</span>
                            </x-button>
                        </form>
                    </div>
                </div>

                <div class="mt-6 text-center text-sm text-gray-500">
                    Don't have an account? 
                    <a href="{{ route('register') }}" wire:navigate class="text-orange-500 font-semibold hover:underline">
                        Create an account
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // OTP Input Handler
        function otpInput() {
            return {
                handleInput(event, nextRef) {
                    const value = event.target.value;
                    if (value && nextRef) {
                        this.$refs[nextRef].focus();
                    }
                    this.updateOtp();
                },
                handleBackspace(event, prevRef) {
                    if (!event.target.value && prevRef) {
                        this.$refs[prevRef].focus();
                    }
                    this.updateOtp();
                },
                updateOtp() {
                    const otp = [
                        this.$refs.digit1.value,
                        this.$refs.digit2.value,
                        this.$refs.digit3.value,
                        this.$refs.digit4.value,
                        this.$refs.digit5.value,
                        this.$refs.digit6.value
                    ].join('');
                    this.$refs.hiddenOtp.value = otp;
                    this.$refs.hiddenOtp.dispatchEvent(new Event('input'));
                }
            }
        }

        // Countdown Timer
        function countdown() {
            return {
                seconds: 600,
                timeLeft: '10:00',
                interval: null,
                init() {
                    this.startCountdown();
                },
                startCountdown() {
                    this.interval = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                            const mins = Math.floor(this.seconds / 60);
                            const secs = this.seconds % 60;
                            this.timeLeft = mins + ':' + secs.toString().padStart(2, '0') + ' mins';
                        } else {
                            clearInterval(this.interval);
                            this.timeLeft = 'Expired';
                        }
                    }, 1000);
                },
                resetCountdown() {
                    clearInterval(this.interval);
                    this.seconds = 600;
                    this.timeLeft = '10:00';
                    this.startCountdown();
                },
                destroy() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                }
            }
        }

        // Clear OTP inputs on resend
        document.addEventListener('livewire:init', () => {
            Livewire.on('otp-resent', () => {
                const inputs = document.querySelectorAll('input[maxlength="1"]');
                inputs.forEach(input => input.value = '');
                if (inputs.length > 0) {
                    inputs[0].focus();
                }
            });
        });
    </script>
    @endpush
</section>