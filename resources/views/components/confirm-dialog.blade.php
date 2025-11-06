@props([
    'title' => '¿Estás seguro?',
    'message' => 'Esta acción no se puede deshacer.',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar'
])

<div 
    x-data="{ 
        open: false, 
        title: '{{ $title }}',
        message: '{{ $message }}',
        confirmText: '{{ $confirmText }}',
        cancelText: '{{ $cancelText }}',
        onConfirm: null,
        onCancel: null
    }"
    x-show="open"
    x-cloak
    @confirm-dialog.window="
        open = true;
        title = $event.detail.title || '{{ $title }}';
        message = $event.detail.message || '{{ $message }}';
        confirmText = $event.detail.confirmText || '{{ $confirmText }}';
        cancelText = $event.detail.cancelText || '{{ $cancelText }}';
        onConfirm = $event.detail.onConfirm;
        onCancel = $event.detail.onCancel;
    "
    class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    style="display: none;"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div 
        @click.away="open = false"
        @click.stop
        class="relative max-w-2xl w-full rounded-3xl shadow-2xl overflow-hidden bg-white border border-gray-100"
        :style="message.includes('<div') ? 'border-top: 5px solid #9AA644;' : 'border-top: 5px solid #ef4444;'"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <!-- Contenido del modal -->
        <div class="p-10">
            <!-- Icono centrado grande con animación (solo si no hay HTML personalizado) -->
            <div class="flex justify-center mb-6" x-show="!message.includes('<div')">
                <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-secondary-400 via-secondary-500 to-secondary-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                    <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <!-- Título centrado con mejor estilo -->
            <h3 class="text-2xl font-extrabold text-center mb-4 tracking-tight" 
                :class="message.includes('<div') ? 'text-secondary-700' : 'text-red-700'"
                x-text="title"></h3>
            
            <!-- Mensaje principal centrado con mejor tipografía -->
            <div 
                :class="message.includes('<div') ? 'text-left' : 'text-center'"
                class="text-gray-700 text-base leading-relaxed mb-8 font-normal px-2"
                x-html="message"
                style="line-height: 1.7;"
            ></div>
            
            <!-- Botones mejorados -->
            <div class="flex justify-center gap-4 pt-2">
                <button
                    @click="open = false; if (onCancel) onCancel();"
                    class="px-8 py-3.5 text-base font-bold text-gray-700 bg-gray-100 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl hover:bg-gray-200 transform hover:scale-105 active:scale-95"
                >
                    <span x-text="cancelText"></span>
                </button>
                <button
                    @click="open = false; if (onConfirm) onConfirm();"
                    class="px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl shadow-secondary-300 transform hover:scale-105 active:scale-95"
                >
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
        
        <!-- Botón cerrar mejorado en esquina -->
        <button
            @click="open = false; if (onCancel) onCancel();"
            class="absolute top-5 right-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors duration-150 p-2 hover:bg-gray-100 rounded-full"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Función global para confirmaciones
    window.confirmAction = function(options) {
        return new Promise((resolve, reject) => {
            const event = new CustomEvent('confirm-dialog', {
                detail: {
                    title: options.title || '¿Estás seguro?',
                    message: options.message || 'Esta acción no se puede deshacer.',
                    confirmText: options.confirmText || 'Confirmar',
                    cancelText: options.cancelText || 'Cancelar',
                    onConfirm: () => resolve(true),
                    onCancel: () => reject(false)
                }
            });
            window.dispatchEvent(event);
        });
    };
    
    // Helper para confirmaciones de eliminación
    window.confirmDelete = function(message, callback) {
        confirmAction({
            title: '¿Estás seguro?',
            message: message || '¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.',
            confirmText: 'Sí, eliminar',
            cancelText: 'Cancelar'
        })
        .then(() => {
            if (callback) callback();
        })
        .catch(() => {
            // Usuario canceló
        });
    };
</script>
@endpush

