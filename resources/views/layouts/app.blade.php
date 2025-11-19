<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    @php
        $faviconPath = public_path('favicon.ico');
        $faviconUrl = file_exists($faviconPath) ? asset('favicon.ico') . '?v=' . filemtime($faviconPath) : asset('favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased" x-data="{ isSidebarOpen: true }" x-init="isSidebarOpen = true">
    <div class="h-screen bg-gray-100 flex overflow-hidden">
        <!-- Sidebar -->
        <x-sidebar />
        
        <!-- Overlay eliminado para evitar oscurecimiento -->
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out" 
             :class="{ 'ml-64': isSidebarOpen, 'ml-0': !isSidebarOpen }">
            
            <!-- Navigation -->
            <div class="sticky top-0 z-40 bg-white shadow">
                <livewire:layout.navigation />
            </div>

            <!-- Page Content (incluye header dentro del área scrolleable) -->
            <main class="flex-1 overflow-y-auto">
                @if (isset($header))
                    <header>
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{ $slot }}
            </main>
        </div>
        
        <!-- Sistema de notificaciones -->
        <x-notification-system />
        
        <!-- Diálogo de confirmación -->
        <x-confirm-dialog />
    </div>
    @livewireScripts
    @stack('scripts')
    
    <script>
        function updateFaviconInBrowser(faviconUrl) {
            try {
                if (!faviconUrl) {
                    return;
                }
                
                const head = document.head || (document.getElementsByTagName && document.getElementsByTagName('head')[0]);
                if (!head) {
                    return;
                }
                
                const links = document.querySelectorAll("link[rel*='icon']");
                if (links && links.length > 0) {
                    links.forEach(link => {
                        if (link && link.nodeName && link.nodeName.toLowerCase() === 'link') {
                            link.href = faviconUrl;
                        }
                    });
                } else {
                    const link = document.createElement('link');
                    if (link) {
                        link.rel = 'icon';
                        link.type = 'image/x-icon';
                        link.href = faviconUrl;
                        if (head && head.appendChild) {
                            head.appendChild(link);
                        }
                    }
                }
            } catch (error) {
                console.error('Error al actualizar favicon:', error);
            }
        }
        
        if (!window.faviconListenerRegistered) {
            window.faviconListenerRegistered = true;
            
            if (typeof Livewire !== 'undefined') {
                Livewire.on('update-favicon-in-browser', (event) => {
                    let faviconUrl = null;
                    if (Array.isArray(event)) {
                        faviconUrl = event[0]?.url || event[0]?.detail?.url;
                    } else if (typeof event === 'object' && event !== null) {
                        faviconUrl = event.url || event.detail?.url;
                    }
                    updateFaviconInBrowser(faviconUrl);
                });
            } else {
                document.addEventListener('livewire:init', function() {
                    Livewire.on('update-favicon-in-browser', (event) => {
                        let faviconUrl = null;
                        if (Array.isArray(event)) {
                            faviconUrl = event[0]?.url || event[0]?.detail?.url;
                        } else if (typeof event === 'object' && event !== null) {
                            faviconUrl = event.url || event.detail?.url;
                        }
                        updateFaviconInBrowser(faviconUrl);
                    });
                }, { once: true });
            }
        }
    </script>
</body>

</html>
