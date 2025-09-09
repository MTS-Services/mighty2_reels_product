    <section class="flex flex-col justify-center">
        <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-68px)]">
            <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
                style="background-image: url('{{ asset('assets/images/verificason.png') }}')">
            </div>

            <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
                <div class="w-full max-w-xl space-y-8 lg:p-10">

                    <div class="text-left">
                        <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                            Welcome Back.
                        </h2>
                    </div>

                    <div class="flex items-center justify-center h-full">
                        <div class="p-8 rounded-lg text-center">
                            <h1 class="text-4xl font-semibold mb-2 text-gray-900">Code Verification</h1>
                            <p class="text-gray-500 mb-6">
                                Enter OTP (One time password) sent to
                                <span class="font-medium text-black">{{ $email }}</span>
                            </p>

                            <form wire:submit.prevent="verifyOtp" x-data="otpForm()" class="space-y-6 text-center">

                                <div class="flex justify-center space-x-4">
                                    <template x-for="(digit, index) in digits" :key="index">
                                        <input type="text" maxlength="1" x-model="digits[index]" x-ref="input"
                                            @input="onInput(index, $event)" @paste="onPaste($event)"
                                            class="w-12 h-12 text-center text-2xl text-black border border-orange-400 rounded-lg focus:outline-none focus:border-orange-500">
                                    </template>
                                </div>
                                <!-- Rate Limit Information -->
                                <div class="text-center space-y-2">
                                    <div x-data="{ rateLimitInfo: @entangle('getRateLimitInfo') }" class="text-xs text-gray-500">
                                        <template x-if="rateLimitInfo.message">
                                            <p class="font-medium" x-text="rateLimitInfo.message"></p>
                                        </template>
                                    </div>
                                </div>

                                @error('otp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if (session()->has('status'))
                                    <div
                                        class="mt-2 text-sm text-green-600 p-3 bg-green-50 border border-green-200 rounded-md">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="mt-2 text-sm text-red-600 p-3 bg-red-50 border border-red-200 rounded-md">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            {{-- <p class="text-black"> {{ $resendCooldown }} <span x-text="$wire.resendCooldown"></span></p> --}}

                                <div class="flex gap-3 justify-between">
                                    <x-button variant="secondary" type="button" class="w-full mt-3 relative"
                                        @click="resendOtp()" :disabled="!$canResend">
                                        <span x-show="!$wire.canResend && $wire.resendCooldown > 0"
                                            x-text="`Resend in ${$wire.resendCooldown}s`"></span>
                                        <span
                                            x-show="!$wire.canResend && $wire.resendCooldown === 0 && $wire.hourlyAttemptsRemaining === 0"
                                            class="text-xs">Limit Reached</span>
                                        <span x-show="$wire.canResend">Resend Code</span>
                                    </x-button>
                                    <x-button variant="primary" type="submit" class="w-full mt-3">
                                        <span wire:loading.remove wire:target="verifyOtp">Verify Code</span>
                                        <span wire:loading wire:target="verifyOtp">Verifying...</span>
                                    </x-button>
                                </div>
                            </form>

                            <div class="mt-6 text-center text-sm text-gray-500">
                                Don't have an account?
                                <a href="{{ route('register') }}"
                                    class="text-orange-500 font-semibold hover:underline">Create an account</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            function otpForm() {
                return {
                    digits: ['', '', '', '', '', ''],
                    timerText: '02:00',
                    initialTimerInterval: null,
                    resendTimerInterval: null,

                    onInput(index, event) {
                        const value = event.target.value;
                        if (value.length > 1) {
                            event.target.value = value.charAt(0);
                        }
                        this.digits[index] = event.target.value;

                        if (value !== '' && index < this.digits.length - 1) {
                            this.$refs.input[index + 1].focus();
                        }

                        this.$wire.set('otp', this.digits.join(''));
                    },

                    onPaste(event) {
                        const pasteData = event.clipboardData.getData('text').slice(0, this.digits.length);
                        this.digits = pasteData.split('');
                        this.$wire.set('otp', this.digits.join(''));

                        for (let i = 0; i < this.digits.length; i++) {
                            if (!this.digits[i]) {
                                this.$refs.input[i].focus();
                                break;
                            }
                        }
                        event.preventDefault();
                    },

                    resendOtp() {
                        if (!this.$wire.canResend) return;
                        this.resetInitialTimer();
                        this.$wire.call('resendOtp');
                    },

                    startInitialTimer() {
                        let seconds = 120;
                        this.initialTimerInterval = setInterval(() => {
                            let min = Math.floor(seconds / 60);
                            let sec = seconds % 60;
                            this.timerText = `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
                            if (seconds-- <= 0) {
                                clearInterval(this.initialTimerInterval);
                            }
                        }, 1000);
                    },

                    resetInitialTimer() {
                        if (this.initialTimerInterval) {
                            clearInterval(this.initialTimerInterval);
                        }
                        this.startInitialTimer();
                    },

                    startCooldownTimer(cooldown) {
                        if (this.resendTimerInterval) {
                            clearInterval(this.resendTimerInterval);
                        }

                        let remaining = cooldown;
                        this.resendTimerInterval = setInterval(() => {
                            if (remaining > 0) {
                                this.$wire.set('resendCooldown', remaining);
                                remaining--;
                            } else {
                                clearInterval(this.resendTimerInterval);
                                this.$wire.call('updateRateLimitStatus');
                            }
                        }, 1000);
                    },

                    init() {
                        this.startInitialTimer();

                        // Listen for cooldown start event
                        this.$wire.on('start-cooldown', (event) => {
                            this.startCooldownTimer(event.cooldown);
                        });

                        // Start cooldown timer if already in cooldown
                        if (this.$wire.resendCooldown > 0) {
                            this.startCooldownTimer(this.$wire.resendCooldown);
                        }

                        // Update rate limit status every 5 seconds
                        setInterval(() => {
                            this.$wire.call('updateRateLimitStatus');
                        }, 5000);
                    }
                }
            }
        </script>
    </section>
