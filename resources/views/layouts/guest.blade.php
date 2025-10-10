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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-dark-teal flex items-center justify-center p-4">
            <!-- Contenedor principal -->
            <div class="w-full max-w-md">
                <!-- Título -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-light-gray mb-2">GestionCIC</h1>
                    <p class="text-light-gray/80">Sistema de Gestión de Inventario</p>
                </div>

                <!-- Card de login -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-strong p-8 border border-white/20">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-light-gray/60 text-sm">
                        © {{ date('Y') }} GestionCIC. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
