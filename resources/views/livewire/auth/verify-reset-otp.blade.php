<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-68px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/verificason.png') }}')">
            <!-- OTP verification image -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">

                <!-- Header -->
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Welcome Back.
                    </h2>
                </div>

                <!-- OTP Form Container -->
                <div class="flex items-center justify-center h-full">
                    <div class="p-8 rounded-lg text-center">
                        <h1 class="text-4xl font-semibold mb-2 text-gray-900">Code Verification</h1>
                        <p class="text-gray-500 mb-6">
                            Enter OTP (One time password) sent to 
                            <span class="font-medium text-black">{{ $email }}</span>
                        </p>

                        <form wire:submit.prevent="verifyOtp" x-data="otpForm()" class="space-y-6 text-center">

                            <!-- OTP Inputs -->
                            <div class="flex justify-center space-x-4">
                                <template x-for="(digit, index) in digits" :key="index">
                                    <input type="text" maxlength="1" x-model="digits[index]"
                                        x-ref="input" @input="onInput(index, $event)" @paste.prevent
                                        class="w-12 h-12 text-center text-2xl border border-orange-400 rounded-lg focus:outline-none focus:border-orange-500">
                                </template>
                            </div>

                            <!-- Countdown -->
                            <p class="text-sm text-gray-400 text-right mr-4" x-text="timerText"></p>

                            <!-- Verify Button -->
                            <x-button variant="primary" type="submit" class="w-full mt-3">
                                <span wire:loading.remove wire:target="verifyOtp">Verify Code</span>
                                <span wire:loading wire:target="verifyOtp">Verifying...</span>
                            </x-button>

                            <!-- Resend Button -->
                            {{-- <x-button variant="secondary" type="button" class="w-full mt-3"
                                :disabled="resendCooldown > 0" @click="resendOtp()">
                                <span x-text="resendCooldown > 0 ? `Resend in ${resendCooldown}s` : 'Resend Code'"></span>
                            </x-button> --}}
                        </form>
                        <!-- Footer Link -->
                        <div class="mt-6 text-center text-sm text-gray-500">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-orange-500 font-semibold hover:underline">Create an account</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script>
        function otpForm() {
            return {
                digits: ['', '', '', '', '', ''],
                resendCooldown: @entangle('resendCooldown'),
                timerText: '01:59',
                
                onInput(index, event) {
                    const value = event.target.value;
                    if (value.length > 1) event.target.value = value.charAt(0);
                    if (value !== '' && index < this.digits.length - 1) {
                        this.$refs.input[index + 1].focus();
                    }
                    @this.set('otp', this.digits.join(''));
                },

                resendOtp() {
                    if (this.resendCooldown > 0) return;
                    @this.call('resendOtp');
                },

                init() {
                    // Countdown timer
                    let seconds = 119;
                    const interval = setInterval(() => {
                        let min = Math.floor(seconds / 60);
                        let sec = seconds % 60;
                        this.timerText = `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;
                        if (seconds-- <= 0) clearInterval(interval);
                    }, 1000);
                }
            }
        }
    </script>
</section>
