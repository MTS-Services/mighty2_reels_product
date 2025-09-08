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
                        Provide the email address linked with your account to reset your password.
                    </p>
                </div>

                <!-- Display success message -->
                @if (session()->has('status'))
                    <div class="text-green-600 text-sm mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="mt-8 space-y-6" wire:submit.prevent="sendPasswordResetLink">

                    <div>
                        <label for="email-address" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email-address" type="email" wire:model="email" required
                                class="appearance-none relative block w-full px-5 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Please enter your registered email.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span>Request Password Reset Link</span>
                        </x-button>
                    </div>
                </form>

                <!-- Cancel Button -->
                <div>
                    <button type="button"
                        onclick="window.location='{{ route('login') }}'"
                        class="group relative w-full flex justify-center py-2 px-4 border border-gray-100 text-sm font-medium rounded-md text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 hover:bg-orange-100">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>
