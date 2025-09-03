<section>
    <div class="flex">
        <div >
            <img src="{{ asset('assets/images/header_login.png') }}" alt="images" class="w-[1100px] h-[850px]">
        </div>
        
        <div class="w-1/2 flex items-center justify-center">
            <div class="w-full max-w-md p-8">
                <h2 class="text-4xl font-bold mb-8">Welcome Back.</h2>
                <form>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" id="email"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="balamia@gmail.com">
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-gray-700 font-semibold">Password</label>
                            <a href="#" class="text-sm text-gray-500 hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password"
                                class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
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

                    <button type="submit"
                        class="w-full bg-orange-500 text-white font-bold py-3 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        Login
                    </button>

                    <div class="mt-6 text-center text-gray-500 text-sm">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-white px-2 text-gray-500">OR</span>
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        class="mt-4 w-full border border-gray-300 rounded-md py-2 flex items-center justify-center space-x-2 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <path
                                d="M12.0003 4.47547C14.1205 4.47547 15.8383 5.20253 17.2486 6.54134L19.8292 3.96071C17.7686 2.01394 15.0063 1 12.0003 1C7.6609 1 4.00194 3.4729 2.1147 6.8427L5.66624 9.69994C6.46328 8.04673 8.1654 6.8427 10.0532 6.8427C11.5283 6.8427 12.8727 7.46975 13.9167 8.52873V8.52873C13.842 8.52873 12.0003 8.52873 12.0003 8.52873V11.2384H21.5794C21.6541 11.7583 21.6914 12.2855 21.6914 12.8126C21.6914 13.8828 21.5286 14.9398 21.218 15.9615C19.897 20.3541 16.0353 23 12.0003 23C7.26629 23 3.19539 20.6974 1.25878 17.3824L4.85897 14.5059C5.83615 16.6343 8.18182 17.9272 10.8715 17.9272C13.5613 17.9272 15.8272 16.591 16.9202 14.2884L19.4938 16.9022C17.7516 18.9103 15.0592 20.1472 12.0003 20.1472C8.61461 20.1472 5.58022 18.5721 3.65586 15.9961L1.25878 17.3824" />
                        </svg>
                        <span>Continue With Google</span>
                    </button>

                    <div class="mt-6 text-center text-sm text-gray-500">
                        Don't have an account? <a href="#"
                            class="text-orange-500 font-semibold hover:underline">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
