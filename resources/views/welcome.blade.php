<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full p-6 bg-gray-800 rounded-lg shadow-xl">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-6">Inventory Management Application</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 justify-center">
                {{-- Send user to Register Page --}}
                <a href="{{ route('dashboard') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                    Dashboard
                </a>
              
            </div>

            <div class="mt-4 text-sm text-gray-400">
                <p>© {{ date('Y') }} ATGS Library. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>

</html>
