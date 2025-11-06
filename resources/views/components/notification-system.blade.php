@push('scripts')
<script>
    // Sistema global de notificaciones usando Alpine.js
    document.addEventListener('alpine:init', () => {
        // Verificar que Alpine esté disponible
        if (typeof Alpine === 'undefined') {
            console.error('Alpine.js no está disponible');
            return;
        }
        
        // Store global para notificaciones
        Alpine.store('notifications', {
            items: [],
            
            add(message, type = 'info', duration = 5000, note = null) {
                const id = Date.now() + Math.random();
                const notification = {
                    id,
                    message,
                    type,
                    duration,
                    note,
                    visible: true,
                    progress: 100
                };
                
                this.items.push(notification);
                
                // Auto-remover después de la duración
                if (duration > 0) {
                    const intervalId = setInterval(() => {
                        const item = this.items.find(n => n.id === id);
                        if (item) {
                            item.progress -= 100 / (duration / 100);
                            if (item.progress <= 0) {
                                clearInterval(intervalId);
                                this.remove(id);
                            }
                        } else {
                            clearInterval(intervalId);
                        }
                    }, 100);
                    
                    // Guardar el ID del intervalo para poder limpiarlo después
                    const item = this.items.find(n => n.id === id);
                    if (item) {
                        item.intervalId = intervalId;
                    }
                }
                
                return id;
            },
            
            remove(id) {
                const index = this.items.findIndex(n => n.id === id);
                if (index > -1) {
                    // Si hay intervalo de progreso, limpiarlo
                    const item = this.items[index];
                    if (item && item.intervalId) {
                        clearInterval(item.intervalId);
                    }
                    
                    // Cerrar cualquier modal abierto cuando se cierra la notificación
                    window.dispatchEvent(new CustomEvent('close-modal'));
                    
                    // Cerrar inmediatamente sin animación de salida
                    this.items.splice(index, 1);
                }
            },
            
            // Métodos de conveniencia
            success(message, duration = 5000) {
                return this.add(message, 'success', duration);
            },
            
            error(message, duration = 7000) {
                return this.add(message, 'error', duration);
            },
            
            warning(message, duration = 6000) {
                return this.add(message, 'warning', duration);
            },
            
            info(message, duration = 5000) {
                return this.add(message, 'info', duration);
            }
        });
        
        // Funciones globales para usar desde cualquier lugar
        window.notify = function(message, type = 'info', duration = 5000) {
            try {
                if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                    return Alpine.store('notifications').add(message, type, duration);
                } else {
                    console.warn('Alpine store not ready yet');
                    return null;
                }
            } catch (e) {
                console.error('Error showing notification:', e);
                return null;
            }
        };
        
        window.notifySuccess = function(message, duration = 5000) {
            try {
                if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                    return Alpine.store('notifications').success(message, duration);
                } else {
                    console.warn('Alpine store not ready yet');
                    return null;
                }
            } catch (e) {
                console.error('Error showing success notification:', e);
                return null;
            }
        };
        
        window.notifyError = function(message, duration = 7000) {
            try {
                if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                    return Alpine.store('notifications').error(message, duration);
                } else {
                    console.warn('Alpine store not ready yet');
                    return null;
                }
            } catch (e) {
                console.error('Error showing error notification:', e);
                return null;
            }
        };
        
        window.notifyWarning = function(message, duration = 6000) {
            try {
                if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                    return Alpine.store('notifications').warning(message, duration);
                } else {
                    console.warn('Alpine store not ready yet');
                    return null;
                }
            } catch (e) {
                console.error('Error showing warning notification:', e);
                return null;
            }
        };
        
        window.notifyInfo = function(message, duration = 5000) {
            try {
                if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                    return Alpine.store('notifications').info(message, duration);
                } else {
                    console.warn('Alpine store not ready yet');
                    return null;
                }
            } catch (e) {
                console.error('Error showing info notification:', e);
                return null;
            }
        };
        
        // Escuchar eventos de Livewire
        window.addEventListener('notify', (e) => {
            const { message, type, duration } = e.detail;
            Alpine.store('notifications').add(message, type || 'info', duration || 5000);
        });
    });
</script>
@endpush

<div x-data x-init="$store.notifications">
    <template x-for="notification in $store.notifications.items" :key="notification.id">
    <div
        x-show="notification.visible"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;"
    >
        <div 
            @click.away="notification.duration === 0 && $store.notifications.remove(notification.id)"
            @click.stop
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            :class="{
                // Success - usa tus colores secondary (verde)
                'bg-white border-secondary-500': notification.type === 'success',
                // Error - usa danger
                'bg-white border-red-500': notification.type === 'error',
                // Warning - usa warning
                'bg-white border-warning-500': notification.type === 'warning',
                // Info - usa tus colores primary (azul acero)
                'bg-white border-primary-500': notification.type === 'info'
            }"
            class="relative max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden bg-white border border-gray-100"
            :style="{
                'border-top': notification.type === 'success' ? '5px solid #9AA644' : 
                            notification.type === 'error' ? '5px solid #ef4444' : 
                            notification.type === 'warning' ? '5px solid #eab308' : 
                            '5px solid #306073'
            }"
        >
            <!-- Barra de progreso (opcional, solo si duration > 0) -->
            <div 
                x-show="notification.duration > 0"
                class="absolute top-0 left-0 right-0 h-1 bg-gray-100 overflow-hidden"
            >
                <div 
                    :style="`width: ${notification.progress}%`"
                    :class="{
                        'bg-secondary-500': notification.type === 'success',
                        'bg-red-500': notification.type === 'error',
                        'bg-warning-500': notification.type === 'warning',
                        'bg-primary-500': notification.type === 'info'
                    }"
                    class="h-full transition-all duration-100 ease-linear rounded-full"
                ></div>
            </div>
            
            <!-- Contenido del modal -->
            <div class="p-10">
                <!-- Icono centrado grande con animación -->
                <div class="flex justify-center mb-6">
                    <template x-if="notification.type === 'success'">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-secondary-400 via-secondary-500 to-secondary-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                            <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="notification.type === 'error'">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-400 via-red-500 to-red-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                            <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="notification.type === 'warning'">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-warning-400 via-warning-500 to-warning-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                            <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="notification.type === 'info'">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-primary-400 via-primary-500 to-primary-600 shadow-xl transform transition-transform duration-300 hover:scale-110">
                            <svg class="w-12 h-12 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </template>
                </div>
                
                <!-- Título centrado con mejor estilo -->
                <h3 
                    :class="{
                        'text-secondary-700': notification.type === 'success',
                        'text-red-700': notification.type === 'error',
                        'text-warning-700': notification.type === 'warning',
                        'text-primary-700': notification.type === 'info'
                    }"
                    class="text-3xl font-extrabold text-center mb-3 tracking-tight"
                    x-text="notification.type === 'success' ? '¡Excelente!' : notification.type === 'error' ? 'Ups, algo salió mal' : notification.type === 'warning' ? 'Atención' : 'Información'"
                ></h3>
                
                <!-- Mensaje principal centrado con mejor tipografía -->
                <p 
                    class="text-gray-700 text-center text-base leading-relaxed mb-6 font-normal px-2"
                    x-text="notification.message"
                    style="line-height: 1.7;"
                ></p>
                
                <!-- Nota adicional mejorada (si existe) -->
                <div 
                    x-show="notification.note"
                    class="mb-6 p-4 bg-gradient-to-r from-warning-50 to-warning-100 border-l-4 border-warning-500 rounded-lg shadow-sm"
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-warning-500">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p 
                                class="text-warning-900 text-sm leading-relaxed font-medium"
                                x-text="notification.note"
                                style="line-height: 1.6;"
                            ></p>
                        </div>
                    </div>
                </div>
                
                <!-- Botón de acción mejorado -->
                <div class="flex justify-center pt-2">
                    <button
                        @click="$store.notifications.remove(notification.id); $event.stopPropagation();"
                        :class="{
                            'bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 shadow-secondary-300': notification.type === 'success',
                            'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-red-300': notification.type === 'error',
                            'bg-gradient-to-r from-warning-500 to-warning-600 hover:from-warning-600 hover:to-warning-700 shadow-warning-300': notification.type === 'warning',
                            'bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-primary-300': notification.type === 'info'
                        }"
                        class="px-10 py-3.5 text-base font-bold text-white rounded-2xl transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95"
                    >
                        Entendido
                    </button>
                </div>
            </div>
            
            <!-- Botón cerrar mejorado en esquina -->
            <button
                @click="$store.notifications.remove(notification.id); $event.stopPropagation();"
                class="absolute top-5 right-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors duration-150 p-2 hover:bg-gray-100 rounded-full"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
    </template>
</div>

<!-- Auto-display de mensajes de sesión -->
@if (session('success'))
<script>
    (function() {
        const message = @json(session('success'));
        const note = @json(session('success_note'));
        function showSuccessNotification() {
            if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                if (note) {
                    window.Alpine.store('notifications').add(message, 'success', 7000, note);
                } else {
                    window.notifySuccess(message);
                }
            } else {
                setTimeout(showSuccessNotification, 100);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSuccessNotification);
        } else {
            document.addEventListener('alpine:initialized', showSuccessNotification);
            setTimeout(showSuccessNotification, 500);
        }
    })();
</script>
@endif

@if (session('error'))
<script>
    (function() {
        const message = @json(session('error'));
        function showErrorNotification() {
            if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                window.notifyError(message);
            } else {
                setTimeout(showErrorNotification, 100);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showErrorNotification);
        } else {
            document.addEventListener('alpine:initialized', showErrorNotification);
            setTimeout(showErrorNotification, 500);
        }
    })();
</script>
@endif

@if (session('warning'))
<script>
    (function() {
        const message = @json(session('warning'));
        function showWarningNotification() {
            if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                window.notifyWarning(message);
            } else {
                setTimeout(showWarningNotification, 100);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showWarningNotification);
        } else {
            document.addEventListener('alpine:initialized', showWarningNotification);
            setTimeout(showWarningNotification, 500);
        }
    })();
</script>
@endif

@if (session('info'))
<script>
    (function() {
        const message = @json(session('info'));
        function showInfoNotification() {
            if (window.Alpine && window.Alpine.store && window.Alpine.store('notifications')) {
                window.notifyInfo(message);
            } else {
                setTimeout(showInfoNotification, 100);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showInfoNotification);
        } else {
            document.addEventListener('alpine:initialized', showInfoNotification);
            setTimeout(showInfoNotification, 500);
        }
    })();
</script>
@endif

