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
                molaris: {
                    dark: '#0A3A60',      // Azul marino principal
                    primary: '#0D4871',   // Azul institucional
                    accent: '#38BDF8',    // Cyan Neón
                    mint: '#2BB673',      // Verde menta
                    mintLight: '#E8F8F0', // Fondo verde suave
                    bg: '#F4F9F9',        // Fondo general
                }
            }
        },
    },

    plugins: [forms],
};