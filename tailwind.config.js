import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,jsx,ts,tsx,vue}', // <-- IMPORTANTE: Escanea código JS/Vue/React del Odontograma
    ],

    // SAFELIST: Evita que Tailwind borre los colores del odontograma si se usan dinámicamente
    safelist: [
        // Rellenos SVG y fondos
        'fill-emerald-500', 'fill-red-500', 'fill-sky-500', 'fill-slate-800', 'fill-slate-200', 'fill-amber-500',
        'bg-emerald-500', 'bg-red-500', 'bg-sky-500', 'bg-slate-800', 'bg-slate-200', 'bg-amber-500',
        'bg-emerald-50', 'bg-red-50', 'bg-sky-50', 'bg-slate-100',
        'border-emerald-500', 'border-red-500', 'border-sky-500', 'border-slate-800',
        'text-emerald-600', 'text-red-600', 'text-sky-600', 'text-slate-800',
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