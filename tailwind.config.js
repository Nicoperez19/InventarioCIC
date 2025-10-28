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
                // Paleta principal del sistema
                primary: {
                    50: '#f0f4f6',   // Muy claro azul acero
                    100: '#d1dde2', // Claro azul acero
                    200: '#a3bbc5', // Medio claro azul acero
                    300: '#7599a8', // Medio azul acero
                    400: '#47778b', // Medio oscuro azul acero
                    500: '#306073', // Azul acero principal
                    600: '#264d5c', // Oscuro azul acero
                    700: '#1c3a45', // Muy oscuro azul acero
                    800: '#12272e', // Casi negro azul acero
                    900: '#081417', // Negro azul acero
                },
                secondary: {
                    50: '#f4f5f0',   // Muy claro verde
                    100: '#e8ebd6', // Claro verde
                    200: '#d1d7ad', // Medio claro verde
                    300: '#bac384', // Medio verde
                    400: '#a3af5b', // Medio oscuro verde
                    500: '#9AA644', // Verde principal
                    600: '#7a8536', // Oscuro verde
                    700: '#5b6429', // Muy oscuro verde
                    800: '#3c431c', // Casi negro verde
                    900: '#1d220e', // Negro verde
                },
                neutral: {
                    50: '#fafafa',   // Blanco puro
                    100: '#f5f5f5',  // Gris muy claro
                    200: '#e5e5e5',  // Gris claro
                    300: '#d4d4d4',  // Gris medio claro
                    400: '#a3a3a3',  // Gris medio
                    500: '#737373',  // Gris medio oscuro
                    600: '#525252',  // Gris oscuro
                    700: '#404040',  // Gris muy oscuro
                    800: '#262626',  // Casi negro
                    900: '#171717',  // Negro
                },
                // Colores semánticos basados en la paleta principal
                success: {
                    50: '#f4f5f0',
                    100: '#e8ebd6',
                    200: '#d1d7ad',
                    300: '#bac384',
                    400: '#a3af5b',
                    500: '#9AA644',
                    600: '#7a8536',
                    700: '#5b6429',
                    800: '#3c431c',
                    900: '#1d220e',
                },
                info: {
                    50: '#f0f4f6',
                    100: '#d1dde2',
                    200: '#a3bbc5',
                    300: '#7599a8',
                    400: '#47778b',
                    500: '#306073',
                    600: '#264d5c',
                    700: '#1c3a45',
                    800: '#12272e',
                    900: '#081417',
                },
                warning: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#eab308',
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                // Colores específicos del sistema (compatibilidad)
                'steel-blue': '#306073',
                'sage-green': '#9AA644',
                'white': '#ffffff',
                'black': '#000000',
            },
        },
    },

    plugins: [forms],
};