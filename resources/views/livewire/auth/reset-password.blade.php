<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/register.png') }}')">
            <!-- You can replace with your reset password image -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                
                <!-- Header -->
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Reset Password
                    </h2>
                    <p class="mt-4 text-sm text-gray-500">
                        Please enter your new password below.
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

                @if ($errors->has('email') || $errors->has('token'))
                    <div class="text-red-600 text-sm mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        @if($errors->has('token'))
                            {{ $errors->first('token') }}
                        @else
                            {{ $errors->first('email') }}
                        @endif
                    </div>
                @endif

                <!-- Form -->
                <form class="mt-8 space-y-6" wire:submit.prevent="resetPassword">
                    
                    <!-- Hidden fields for token and email -->
                    <input type="hidden" wire:model="token">
                    <input type="hidden" wire:model="email">

                    <!-- Email Display (text only) -->
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-1 px-5 py-2 bg-gray-50 border border-gray-300 rounded-md">
                            <p class="text-gray-700 text-sm font-medium">{{ $email }}</p>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            This is the email address associated with your password reset request.
                        </p>
                    </div> --}}

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            New Password
                        </label>
                        <div class="mt-1" x-data="{ show: false }">
                            <div class="relative">
                                <input id="password" wire:model="password" 
                                    :type="show ? 'text' : 'password'" required
                                    class="text-black appearance-none relative block w-full px-5 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                    placeholder="Enter your new password">
                                
                                <!-- Toggle Button -->
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer"
                                    @click="show = !show">
                                    <!-- Eye Open -->
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <!-- Eye Slash -->
                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Password must be at least 8 characters long.
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <div class="mt-1" x-data="{ show: false }">
                            <div class="relative">
                                <input id="password_confirmation" wire:model="password_confirmation" 
                                    :type="show ? 'text' : 'password'" required
                                    class="text-black appearance-none relative block w-full px-5 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                    placeholder="Confirm your new password">

                                <!-- Toggle Button -->
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer"
                                    @click="show = !show">
                                    <!-- Eye Open -->
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <!-- Eye Slash -->
                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                            <span wire:loading wire:target="resetPassword">Resetting...</span>
                        </x-button>
                    </div>

                    <!-- Cancel Button -->
                    <div>
                        <button type="button"
                            onclick="window.location='{{ route('login') }}'"
                            class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>