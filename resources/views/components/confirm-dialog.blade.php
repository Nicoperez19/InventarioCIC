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
        :style="title.includes('Aprobación') || title.includes('Entrega') ? 'border-top: 5px solid #306073;' : (title.includes('Rechazo') || title.includes('Eliminar')) ? 'border-top: 5px solid #ef4444;' : (title.includes('Advertencia') || title.includes('Warning')) ? 'border-top: 5px solid #eab308;' : message.includes('<div') ? 'border-top: 5px solid #9AA644;' : 'border-top: 5px solid #306073;'"
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
                <!-- Icono para Aprobación/Entrega - Azul acero (Primary) con icono de información -->
                <template x-if="title.includes('Aprobación') || title.includes('Entrega')">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-primary-400 via-primary-500 to-primary-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                        <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>
                <!-- Icono para Rechazo/Eliminar - Rojo con icono de X -->
                <template x-if="title.includes('Rechazo') || title.includes('Eliminar')">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-400 via-red-500 to-red-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                        <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>
                <!-- Icono para Advertencia - Amarillo con icono de advertencia -->
                <template x-if="title.includes('Advertencia') || title.includes('Warning')">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-warning-400 via-warning-500 to-warning-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                        <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>
                <!-- Icono por defecto - Azul acero (Primary) -->
                <template x-if="!title.includes('Aprobación') && !title.includes('Entrega') && !title.includes('Rechazo') && !title.includes('Eliminar') && !title.includes('Advertencia') && !title.includes('Warning')">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-primary-400 via-primary-500 to-primary-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                        <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>
            </div>
            
            <!-- Título centrado con mejor estilo -->
            <h3 class="text-2xl font-extrabold text-center mb-4 tracking-tight" 
                :class="(title.includes('Aprobación') || title.includes('Entrega')) ? 'text-primary-700' : (title.includes('Rechazo') || title.includes('Eliminar')) ? 'text-red-700' : (title.includes('Advertencia') || title.includes('Warning')) ? 'text-warning-700' : message.includes('<div') ? 'text-secondary-700' : 'text-primary-700'"
                x-text="title"></h3>
            
            <!-- Mensaje principal centrado con mejor tipografía - destacado para aprobación -->
            <div 
                :class="message.includes('<div') ? 'text-left text-gray-700 text-base leading-relaxed mb-8 font-normal px-2' : (title.includes('Aprobación') || title.includes('Entrega')) ? 'text-center bg-primary-50 border-2 border-primary-200 rounded-xl p-6 mb-8 text-primary-900 text-lg leading-relaxed font-semibold' : 'text-center text-gray-700 text-base leading-relaxed mb-8 font-normal px-2'"
                x-html="message"
                :style="(title.includes('Aprobación') || title.includes('Entrega')) && !message.includes('<div') ? 'line-height: 1.8;' : 'line-height: 1.7;'"
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
                    :class="(title.includes('Rechazo') || title.includes('Eliminar')) ? 'px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl shadow-red-300 transform hover:scale-105 active:scale-95' : (title.includes('Advertencia') || title.includes('Warning')) ? 'px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-warning-500 to-warning-600 hover:from-warning-600 hover:to-warning-700 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl shadow-warning-300 transform hover:scale-105 active:scale-95' : 'px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl shadow-primary-300 transform hover:scale-105 active:scale-95'"
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

