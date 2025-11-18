<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $favicon = null;
    public $currentFavicon = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        try {
            // Verificar si existe un favicon personalizado
            $faviconPath = public_path('favicon.ico');
            if (file_exists($faviconPath) && is_readable($faviconPath)) {
                $this->currentFavicon = asset('favicon.ico') . '?v=' . filemtime($faviconPath);
            } else {
                $this->currentFavicon = asset('favicon.ico');
            }
        } catch (\Exception $e) {
            // Si hay algún error, usar el favicon por defecto
            $this->currentFavicon = asset('favicon.ico');
        }
    }

    /**
     * Update the favicon.
     */
    public function updateFavicon(): void
    {
        $this->validate([
            'favicon' => ['required', 'image', 'mimes:ico,png,jpg,jpeg', 'max:3072'], // 3MB max
        ], [
            'favicon.required' => 'Por favor, selecciona un archivo de favicon.',
            'favicon.image' => 'El archivo debe ser una imagen válida.',
            'favicon.mimes' => 'El archivo debe ser de tipo: ICO, PNG, JPG o JPEG.',
            'favicon.max' => 'El archivo no debe ser mayor a 3MB (3072 KB).',
        ]);

        try {
            // Obtener el archivo temporal subido
            $uploadedFile = $this->favicon;
            $targetPath = public_path('favicon.ico');
            
            // Obtener la ruta temporal del archivo (Livewire maneja esto automáticamente)
            $tempPath = $uploadedFile->getRealPath();
            
            // Si no está disponible, usar el método storePath de Livewire
            if (!$tempPath || !file_exists($tempPath)) {
                $tempPath = $uploadedFile->path();
            }
            
            // Verificar que el archivo temporal existe
            if (!$tempPath || !file_exists($tempPath)) {
                throw new \Exception('No se pudo obtener la ruta del archivo temporal');
            }
            
            // Si existe un favicon anterior, eliminarlo primero
            if (file_exists($targetPath)) {
                unlink($targetPath);
            }
            
            // Copiar el nuevo archivo
            if (!copy($tempPath, $targetPath)) {
                throw new \Exception('No se pudo copiar el archivo al destino');
            }

            // Limpiar la propiedad para que el input se resetee
            $this->favicon = null;
            
            // Actualizar la URL del favicon actual
            $this->currentFavicon = asset('favicon.ico') . '?v=' . time();

            session()->flash('favicon-updated', true);
            
            $this->dispatch('favicon-updated');
            
            // Actualizar el favicon en el navegador sin recargar
            $this->dispatch('update-favicon-in-browser', url: asset('favicon.ico') . '?v=' . time());
        } catch (\Exception $e) {
            $this->addError('favicon', 'Error al actualizar el favicon: ' . $e->getMessage());
        }
    }

    /**
     * Remove the custom favicon and restore default.
     */
    public function removeFavicon(): void
    {
        try {
            // Eliminar favicon personalizado si existe
            $faviconPath = public_path('favicon.ico');
            if (file_exists($faviconPath)) {
                unlink($faviconPath);
            }
            
            // También eliminar otros formatos si existen
            $formats = ['png', 'jpg', 'jpeg'];
            foreach ($formats as $format) {
                $path = public_path("favicon.{$format}");
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $this->currentFavicon = asset('favicon.ico');
            session()->flash('favicon-removed', true);
            
            $this->dispatch('favicon-updated');
            
            // Actualizar el favicon en el navegador sin recargar
            $this->dispatch('update-favicon-in-browser', url: asset('favicon.ico') . '?v=' . time());
        } catch (\Exception $e) {
            $this->addError('favicon', 'Error al eliminar el favicon: ' . $e->getMessage());
        }
    }
}; ?>

<section>
    <script>
        // Escuchar el evento para actualizar el favicon en el navegador
        document.addEventListener('livewire:init', function() {
            Livewire.on('update-favicon-in-browser', (event) => {
                try {
                    // Extraer la URL del evento
                    let faviconUrl = null;
                    if (Array.isArray(event)) {
                        faviconUrl = event[0]?.url || event[0]?.detail?.url;
                    } else if (typeof event === 'object' && event !== null) {
                        faviconUrl = event.url || event.detail?.url;
                    }
                    
                    if (!faviconUrl) {
                        console.warn('No se pudo obtener la URL del favicon del evento');
                        return;
                    }
                    
                    // Obtener el elemento head de forma segura
                    const head = document.head || (document.getElementsByTagName && document.getElementsByTagName('head')[0]);
                    if (!head) {
                        console.warn('No se pudo encontrar el elemento head');
                        return;
                    }
                    
                    // Actualizar todos los links de favicon existentes
                    const links = document.querySelectorAll("link[rel*='icon']");
                    if (links && links.length > 0) {
                        links.forEach(link => {
                            if (link && link.nodeName && link.nodeName.toLowerCase() === 'link') {
                                link.href = faviconUrl;
                            }
                        });
                    } else {
                        // Si no hay ningún link, crear uno nuevo
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
            });
        });
    </script>
    <header class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Icono del Sistema') }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ __('Personaliza el icono que aparece en la pestaña del navegador.') }}
                </p>
            </div>
        </div>
    </header>

    <form wire:submit="updateFavicon" class="space-y-6">
               <!-- Input para subir nuevo favicon -->
        <div>
            <x-input-label for="favicon" :value="__('Nuevo Icono')" class="text-sm font-medium text-gray-700 mb-2" />
            <div class="mt-2">
                <input wire:model="favicon" 
                       type="file" 
                       id="favicon" 
                       name="favicon"
                       accept=".ico,.png,.jpg,.jpeg"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            </div>
            <p class="mt-2 text-xs text-gray-500">
                Formatos soportados: ICO, PNG, JPG, JPEG (máximo 3MB). Se recomienda usar formato ICO para mejor compatibilidad.
            </p>
            @error('favicon')
                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-800 font-medium">{{ $message }}</p>
                    </div>
                </div>
            @enderror
            
            @if($favicon)
                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-blue-800">
                            Archivo seleccionado: <span class="font-medium">{{ is_object($favicon) ? $favicon->getClientOriginalName() : 'Archivo' }}</span>
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200 space-x-4">
            <div>
                <x-action-message class="text-sm text-green-600 font-medium" on="favicon-updated">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Favicon actualizado correctamente. Recarga la página para ver los cambios.') }}
                    </div>
                </x-action-message>
                
                @if(session('favicon-removed'))
                    <div class="flex items-center text-sm text-blue-600 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Favicon restaurado al predeterminado.') }}
                    </div>
                @endif
            </div>
            
            <div class="flex items-center space-x-3">
                @if($currentFavicon && file_exists(public_path('favicon.ico')))
                    <button type="button" 
                            wire:click="removeFavicon"
                            wire:confirm="¿Estás seguro de que deseas eliminar el favicon personalizado y restaurar el predeterminado?"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        {{ __('Restaurar Predeterminado') }}
                    </button>
                @endif
                
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="updateFavicon">
                        {{ __('Guardar Icono') }}
                    </span>
                    <span wire:loading wire:target="updateFavicon" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Guardando...') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</section>

