<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class=" text-gray-800">

    <!-- Main Container -->
    <div class="flex flex-col md:flex-row h-screen">

        <!-- Left Column: Image -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center " style="background-image: url('{{ asset('assets/images/Forget_Password.png') }}">
            <!-- You can replace the placeholder image with your own -->
        </div>

        <!-- Right Column: Form -->
        <div class="w-full md:w-1/2 flex items-center justify-left p-8">
            <div class="w-full max-w-md space-y-8 p-10   ">
                <div class="text-left">
                    <h2 class="mt-6 text-4xl font-semibold text-gray-900">
                        Forgot Password
                    </h2>
                    <p class="mt-4 text-sm text-gray-500">
                        Provide the email address linked with your account to reset your password.
                    </p>
                </div>

                <form class="mt-8 space-y-6" action="#" method="POST">
                    <div>
                        <label for="email-address" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Enter your email">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Please enter your registered email.
                        </p>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Request Password Reset Link
                        </button>
                    </div>
                </form>

                <div>
                    <button type="button"
                        class="group relative w-full flex justify-center py-2 px-4 border border-gray-100 text-sm font-medium rounded-md text-gray-700 bg-white  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 hover:bg-orange-100">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
