<section class="min-h-screen flex items-center justify-center bg-gray-100 p-4 sm:p-6 md:p-10">
    <div class="flex">
        <div class="hidden sm:block lg:w-1/2">
            <img src="{{ asset('assets/images/header_login.png') }}" alt="images" class="w-[1100px] h-[881px]">
        </div>

        <div class="lg:w-1/2 flex items-center justify-center mx-auto">
            <div class="w-full max-w-md p-8">
                <h2 class="text-5xl font-semibold mb-8 text-gray-900">Welcome Back.</h2>
                <form>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" id="email"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-500"
                            placeholder="Enter your email">
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-gray-700 font-semibold">Password</label>
                            <a href="#" class="text-sm text-gray-500 hover:underline">Forgot password?</a>
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

                    <x-button type="submit" class="w-full">
                        Login
                    </x-button>

                    <x-button variant="secondary" class="w-full mt-3">
                        <img src="{{ asset('assets/images/google.svg') }}" alt="google" class="w-5 h-5">
                        <span>Continue With Google</span>  
                    </x-button>

                    <div class="mt-6 text-center text-sm text-gray-500">
                        Don't have an account? <a href="#"
                            class="text-orange-500 font-semibold hover:underline">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
