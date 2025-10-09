import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Tu paleta personalizada - colores directos
                'dark-black': '#0D0D0D',
                'dark-teal': '#1A5A73',
                'navy-blue': '#010326',
                'light-cyan': '#339AA6',
                'golden-orange': '#F2A74B',
                'olive-green': '#637329',
                'light-gray': '#f2f2f2',
                
                // Colores principales con variaciones básicas
                primary: {
                    500: '#339AA6', // light-cyan
                    600: '#1A5A73', // dark-teal
                    700: '#010326', // navy-blue
                    900: '#0D0D0D', // dark-black
                },
                secondary: {
                    500: '#F2A74B', // golden-orange
                    600: '#d97706',
                },
                success: {
                    500: '#637329', // olive-green
                    600: '#4d5a1f',
                },
                danger: {
                    500: '#ef4444',
                    600: '#dc2626',
                },
                neutral: {
                    200: '#f2f2f2', // light-gray
                    500: '#737373',
                    900: '#0D0D0D', // dark-black
                },
                // Colores específicos del sistema (compatibilidad)
                'light-cloud-blue': '#339AA6',
                'steel-blue': '#1A5A73',
                'green-add': '#637329',
            },
        },
    },

    plugins: [forms],
};