<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center "
            style="background-image: url('{{ asset('assets/images/header_login.png') }}">
            <!-- You can replace the placeholder image with your own -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Welcome Back.
                    </h2>
                </div>

                <form class="mt-8 space-y-6" wire:submit="login" method="POST">

                    <div>
                        <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email-address" name="email" wire:model="email" type="email"
                                autocomplete="email" required
                                class="appearance-none text-gray-900 relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Please enter your registered email.
                        </p>
                    </div>
                    <div class="flex justify-between items-center mb-2">

                        <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-sm text-gray-500 hover:underline">Forgot password?</a>
                    </div>


                    <div class="mt-1" x-data="{ show: false }">
                        <div class="relative">
                            <input id="password" wire:model="password" :type="show ? 'text' : 'password'" required
                                class="text-black appearance-none relative block w-full px-5 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your new password">

                            <!-- Toggle Button -->
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer"
                                @click="show = !show">
                                <!-- Eye Open -->
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <!-- Eye Slash -->
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center mb-6">
                        <input type="checkbox" id="remember"
                            class="h-4 w-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember password</label>
                    </div>

                    <div>
                        <x-button variant="primary" type="submit" class="w-full mt-3">
                            <span>Login</span>
                        </x-button>
                    </div>


                </form>
                <x-button variant="secondary" href="{{route('auth.social.redirect', 'google')}}" class="w-full mt-3">
                    <img src="{{ asset('assets/images/google.svg') }}" alt="google" class="w-5 h-5">
                    <span>Continue With Google</span>
                </x-button>

                <div class="mt-6 text-center text-sm text-gray-500">
                    Don't have an account? <a href="{{ route('register') }}" wire:navigate
                        class="text-orange-500 font-semibold hover:underline">Create an account</a>
                </div>
            </div>
        </div>
    </div>
</section>
