
<section>
    <!-- Main Container -->
    <div class="flex flex-col justify-center sm:justify-start md:flex-row h-[calc(100vh-66px)]">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center " style="background-image: url('{{ asset('assets/images/bgforget.png') }}">
            <!-- You can replace the placeholder image with your own -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-1/3 flex items-center justify-left p-8">
            <div class="w-full max-w-xl space-y-8 lg:p-10">
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                       Verify Your Email
                    </h2>
                    <p class="mt-4 text-sm text-gray-500">
                        You will receive an email with a verification code to reset your password. Please check your inbox.
                    </p>
                </div>

                <form class="mt-8 space-y-6" action="#" method="POST">
                     

                    <div>
                        {{-- <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Resend Verification Code
                        </button> --}}

                        <x-button variant="primary" class="w-full mt-3">
                            <span>Resend Verification Code</span>
                        </x-button>
                    </div>
                </form>

                <div>
                    {{-- <button type="button"
                        class="group relative w-full flex justify-center py-2 px-4 border border-gray-100 text-sm font-medium rounded-md  bg-white  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 hover:bg-orange-100 text-orange-500">
                        Change Email Address
                    </button> --}}

                    <x-button variant="secondary" class="w-full mt-3">
                        <span>Change Email Address</span>  
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>


