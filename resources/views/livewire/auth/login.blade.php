<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center " style="background-image: url('{{ asset('assets/images/header_login.png') }}">
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

                <form class="mt-8 space-y-6" action="#" method="POST">
                    <div>
                        <label for="email-address" class="block  text-sm font-medium text-gray-700 ">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full p-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Please enter your registered email.
                        </p>
                    </div>

                     <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-gray-700 font-semibold text-sm">Password</label>
                            <a href="{{ route('password.request') }}"  wire:navigate class="text-sm text-gray-500 hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password"
                                class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-500" 
                                placeholder="Enter your password">
                            <span
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.575 3.01 9.963 7.822.09.304.09.638 0 .942-.252.793-.687 1.5-1.298 2.098a12.822 12.822 0 01-1.22 1.157c-1.353 1.171-2.906 2.05-4.664 2.512a14.75 14.75 0 01-5.181 0c-1.758-.462-3.311-1.341-4.664-2.512a12.822 12.822 0 01-1.22-1.157c-.611-.598-1.046-1.305-1.298-2.098zM12 9a3 3 0 100 6 3 3 0 000-6z" />
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
                        <button type="submit"
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Login
                        </button>
                    </div>

                    <x-button variant="secondary" class="w-full mt-3">
                        <img src="{{ asset('assets/images/google.svg') }}" alt="google" class="w-5 h-5">
                        <span>Continue With Google</span>  
                    </x-button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                        Don't have an account? <a href="#"
                            class="text-orange-500 font-semibold hover:underline">Create an account</a>
                    </div>
            </div>
        </div>
    </div>
</section>


