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
                                class="appearance-none relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Please enter your registered email.
                        </p>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">

                            <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" wire:navigate
                                class="text-sm text-gray-500 hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" wire:model="password" type="password"
                                autocomplete="email" required
                                class="appearance-none relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your password">
                            <span
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer">
                                <flux:icon name="eye" class="w-5 h-5" />
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
                <x-button variant="secondary" class="w-full mt-3">
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
