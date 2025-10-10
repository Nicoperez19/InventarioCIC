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

<body class="font-sans antialiased" x-data="{ isSidebarOpen: true }">
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <livewire:layout.sidebar />
        
        <!-- Overlay para difuminado cuando sidebar está abierto -->
        <div class="fixed inset-0 bg-black bg-opacity-20 transition-opacity duration-300 ease-in-out z-40"
             :class="{ 'opacity-100': isSidebarOpen, 'opacity-0 pointer-events-none': !isSidebarOpen }"
             x-show="isSidebarOpen"
             x-on:click="isSidebarOpen = false"
             x-transition:enter="transition-opacity duration-300 ease-in-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300 ease-in-out"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out" 
             :class="{ 'ml-64 brightness-75': isSidebarOpen, 'ml-0 brightness-100': !isSidebarOpen }">
            
            <!-- Navigation -->
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
