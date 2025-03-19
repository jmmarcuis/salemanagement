<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <!-- Page Loader -->
    <div id="page-loader" class="page-loader hidden">
        <div class="spinner"></div>
    </div>

    <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <livewire:layout.navigation />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Ensure the main content expands fully -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                {{ $slot }}
                @livewireScripts
            </main>
        </div>
    </div>

    <!-- Centralized SweetAlert2 Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {

            // Error alerts
            Livewire.on('swal:error', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });

            // Confirmation alerts
            Livewire.on('swal:confirm', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].icon || 'warning',
                    showCancelButton: true,
                    confirmButtonColor: data[0].confirmButtonColor || '#d33',
                    cancelButtonColor: data[0].cancelButtonColor || '#3085d6',
                    confirmButtonText: data[0].confirmButtonText || 'Yes',
                    cancelButtonText: data[0].cancelButtonText || 'Cancel',

                }).then((result) => {
                    if (result.isConfirmed) {
                        if (data[0].method && data[0].params) {
                        console.log("IM CALLED!")
                        console.log(data)
                            Livewire.dispatch(data[0].method, data[0].params);
                        }
                    }
                });

            });

            // Future feature alerts
            Livewire.on('swal:future', (data) => {
                Swal.fire({
                    title: data[0].title || 'Coming Soon!',
                    text: data[0].text || 'This feature is under development.',
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Got it!'
                });
            });

            // Loading State
            Livewire.on('swal:loading', () => {
                console.log("Im called")
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Processing your request.',
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,

                });
            });

            // Update the Loading state to either Success or Failure
            //  ! MUST STATE PARAMETERS THIS IN THE PHP METHOD IN THE COMPONENT !!!!
            Livewire.on('swal:message', (data) => {
                console.log("Message Relayed...")
                setTimeout(() => {
                    // Update the existing Swal alert
                    Swal.update(data[0]);
                }, 1000); // Update after 1 second to show loading state
            });

            // Update the Loading state to either Success or Failure
            //  ! MUST STATE PARAMETERS IN THE PHP METHOD IN THE COMPONENT !!!!
            Livewire.on('swal:message:redirect', (data) => {
                console.log("Message Relayed...");
                setTimeout(() => {
                    // Update the existing Swal alert
                    Swal.update({
                        title: data[0].title,
                        text: data[0].text,
                        icon: data[0].icon,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Redirect after the user clicks "OK"
                    Swal.getConfirmButton().addEventListener('click', () => {
                        window.location.href = data[0].route;
                    });
                }, 1000); // Update after 1 second to show loading state
            });


            // Success Message
            Livewire.on('swal:success', (data) => {
                console.log("Sucess! modal")

                Swal.fire({

                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].icon,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });


        });
    </script>
</body>

</html>
