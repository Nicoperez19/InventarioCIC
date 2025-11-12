@props(['name', 'show' => false, 'maxWidth' => '2xl', 'title' => null])

@php
    $maxWidthClass =
        [
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
        ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div x-data="modalComponent({ show: @js($show), focusable: {{ $attributes->has('focusable') ? 'true' : 'false' }} })"
    x-init="init()" x-show="show" @open-modal.window="handleOpen($event, '{{ $name }}')" @close-modal.window="handleClose($event, '{{ $name }}')" @close.stop="show = false"
    @keydown.escape.window="show = false" @keydown.tab.prevent="navigateFocus($event)"
    class="fixed inset-0 z-[150] px-4 pt-8 overflow-y-auto sm:px-0" style="display: none;">
    <!-- Background overlay -->
    <div x-show="show" class="fixed inset-0 transition-opacity bg-gray-400 opacity-60 backdrop-blur-sm"
        @click="show = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Modal -->
    <div x-show="show"
        class="mb-6 bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:w-full {{ $maxWidthClass }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="px-6 py-4 text-lg font-semibold text-white bg-primary-500 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ $title ?? ($header ?? '') }}
            </div>
            <button @click="show = false" class="text-white hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenido del modal -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>


</div>

<script>
    function modalComponent({
        show = false,
        focusable = false
    }) {
        return {
            show,
            init() {
                this.$watch('show', value => {
                    document.body.classList.toggle('overflow-y-hidden', value);
                    if (value && focusable) {
                        setTimeout(() => this.firstFocusable()?.focus(), 100);
                    }
                });
            },
            handleOpen(event, name) {
                if (event.detail === name) this.show = true;
            },
            handleClose(event, name) {
                // Si no hay nombre específico o el evento es para este modal, cerrar
                if (!event.detail || event.detail === name || this.show) {
                    this.show = false;
                }
            },
            focusables() {
                return [...this.$el.querySelectorAll(
                    'a, button, input:not([type="hidden"]), textarea, select, details, [tabindex]:not([tabindex="-1"])'
                )].filter(el => !el.disabled);
            },
            firstFocusable() {
                return this.focusables()[0];
            },
            lastFocusable() {
                return this.focusables().at(-1);
            },
            navigateFocus(event) {
                const focusables = this.focusables();
                const index = focusables.indexOf(document.activeElement);
                const direction = event.shiftKey ? -1 : 1;
                const next = (index + direction + focusables.length) % focusables.length;
                focusables[next]?.focus();
            },
        };
    }
</script>