<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Navegación de paginación" class="flex items-center justify-between">
            <div class="flex justify-between flex-1">
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        Anterior
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-white hover:bg-dark-teal hover:border-dark-teal focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan active:bg-light-cyan active:text-white transition ease-in-out duration-150">
                        Anterior
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-white hover:bg-dark-teal hover:border-dark-teal focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan active:bg-light-cyan active:text-white transition ease-in-out duration-150">
                        Siguiente
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                        Siguiente
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
