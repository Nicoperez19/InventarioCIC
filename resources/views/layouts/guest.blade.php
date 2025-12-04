<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InventarioCIC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen bg-gradient-to-b from-secondary-500 via-primary-500 to-primary-700 flex flex-col justify-center items-center p-2 sm:p-3 overflow-hidden">
            <!-- Logo y Título -->
            <div class="text-center mb-2 sm:mb-3 flex-shrink-0">
                <!-- Logo -->
                <div class="flex justify-center mb-1.5 sm:mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo InventarioCIC" class="h-32 sm:h-40 md:h-44 w-auto">
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-0.5 sm:mb-1">InventarioCIC</h1>
                <p class="text-white/90 text-xs sm:text-sm">Sistema de Gestión de Inventario</p>
            </div>

            <!-- Card de login -->
            <div class="w-full max-w-md flex-shrink-0">
                <div class="bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-2xl p-4 sm:p-5 md:p-6 border border-white/30">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-2 sm:mt-3 flex-shrink-0">
                    <p class="text-white/70 text-xs">
                        © {{ date('Y') }} InventarioCIC. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
