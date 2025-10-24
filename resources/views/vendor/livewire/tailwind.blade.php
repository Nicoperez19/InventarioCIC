<div>
    <nav role="navigation" aria-label="Navegación de paginación" class="flex items-center justify-between">
        {{-- Versión móvil --}}
        <div class="flex justify-between flex-1 sm:hidden">
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

        {{-- Versión desktop --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    Mostrando
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-medium">{{ $paginator->count() }}</span>
                    @endif
                    de
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Botón Anterior --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Anterior">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-white hover:bg-dark-teal hover:border-dark-teal focus:z-10 focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan active:bg-light-cyan active:text-white transition ease-in-out duration-150" aria-label="Anterior">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    {{-- Elementos de Paginación --}}
                    @if ($paginator->hasPages())
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-light-cyan border border-light-cyan cursor-default leading-5">{{ $page }}</span>
                                        </span>
                                    @else
                                        <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-white hover:bg-dark-teal hover:border-dark-teal focus:z-10 focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan active:bg-light-cyan active:text-white transition ease-in-out duration-150" aria-label="Ir a la página {{ $page }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        {{-- Si solo hay una página, mostrar el número 1 --}}
                        <span aria-current="page">
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-light-cyan border border-light-cyan cursor-default leading-5">1</span>
                        </span>
                    @endif

                    {{-- Botón Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-white hover:bg-dark-teal hover:border-dark-teal focus:z-10 focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan active:bg-light-cyan active:text-white transition ease-in-out duration-150" aria-label="Siguiente">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="Siguiente">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
</div>
