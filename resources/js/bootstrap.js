import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Alpine.js se carga automáticamente a través de Livewire 3
// Livewire incluye Alpine.js de forma nativa
