<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center " style="background-image: url('{{ asset('assets/images/verificason.png') }}">
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

                 <div class=" flex items-center justify-center min-h-screen">
    <div class="p-8 rounded-lg text-center">
        <h1 class="text-4xl font-semibold mb-2 text-gray-900">Code Verification</h1>
        <p class="text-gray-500 mb-6">Enter OTP (One time password) sent to <span class="font-medium text-black">balamia@gmail.com</span></p>
        <form class="space-y-6">
            <div class="flex justify-center space-x-4">
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
                <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border-1 border-orange-400 rounded-lg focus:outline-none focus:border-orange-500" />
            </div>
            <p class="text-sm text-gray-400 text-right mr-4">1:59 mins</p>
            {{-- <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-bold hover:bg-orange-600 transition duration-200">Verify Code</button> --}}
            
            <x-button variant="primary" class="w-full mt-3">
                <span>Verify Code</span>
            </x-button>

            {{-- <button type="button" class="w-full text-orange-500 py-2 rounded-lg font-bold hover:text-orange-600 transition duration-200">Resend Code</button> --}}

            <x-button variant="secondary" class="w-full mt-3">
                <span>Resend Code</span>  
            </x-button>
        </form>
    </div>
</div>

                <div class="mt-6 text-center text-sm text-gray-500">
                        Don't have an account? <a href="#"
                            class="text-orange-500 font-semibold hover:underline">Create an account</a>
                    </div>
            </div>
        </div>
    </div>
</section>


