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
        <div class="min-h-screen bg-gradient-to-b from-secondary-500 via-primary-500 to-primary-700 flex flex-col justify-center items-center p-4">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <!-- Logo -->
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo GestionCIC" class="h-32 w-auto">
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">GestionCIC</h1>
                <p class="text-white/90 text-lg">Sistema de Gestión de Inventario</p>
            </div>

            <!-- Card de login -->
            <div class="w-full max-w-md">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-white/30">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-white/70 text-sm">
                        © {{ date('Y') }} GestionCIC. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
