<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Solicitud de Insumos') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">
                        @if(auth()->user()->hasRole('jefe-departamento'))
                            Solicita artículos de oficina disponibles
                        @elseif(auth()->user()->hasRole('auxiliar'))
                            Solicita artículos de aseo disponibles
                        @else
                            Solicita insumos disponibles según tu rol
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto flex items-center justify-end space-x-3">
                <button onclick="window.dispatchEvent(new CustomEvent('crear-solicitud'))" 
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 sm:px-5 py-2.5 text-sm font-semibold text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Crear</span>
                    <span class="hidden sm:inline">Crear Solicitud</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8" id="solicitud-wrapper">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8" id="solicitud-container">
            <livewire:tables.solicitud-insumos-table />
        </div>
    </div>
    
    <script>
        function updateContentMargin() {
            const header = document.querySelector('header');
            const container = document.getElementById('solicitud-container');
            const panel = document.querySelector('[class*="fixed"][class*="lg:right-0"]');
            
            if (panel && window.innerWidth >= 1024) {
                const marginRight = '24rem';
                
                // Ajustar header
                if (header) {
                    const headerContent = header.querySelector('.max-w-7xl');
                    if (headerContent) {
                        headerContent.style.marginRight = marginRight;
                        headerContent.style.transition = 'margin-right 0.3s ease';
                    }
                }
                
                // Ajustar contenido principal
                if (container) {
                    container.style.marginRight = marginRight;
                    container.style.transition = 'margin-right 0.3s ease';
                }
            } else {
                // Restaurar valores por defecto
                if (header) {
                    const headerContent = header.querySelector('.max-w-7xl');
                    if (headerContent) {
                        headerContent.style.marginRight = '';
                    }
                }
                
                if (container) {
                    container.style.marginRight = '';
                }
            }
        }
        
        // Observar cambios en el DOM
        const observer = new MutationObserver(updateContentMargin);
        observer.observe(document.body, { childList: true, subtree: true });
        
        // Verificar al cargar y al redimensionar
        window.addEventListener('load', updateContentMargin);
        window.addEventListener('resize', updateContentMargin);
        
        // Verificar periódicamente (fallback)
        setInterval(updateContentMargin, 500);
    </script>
</x-app-layout>



