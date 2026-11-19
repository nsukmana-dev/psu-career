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
                // Admin UI palette (warm coral "studio" theme) — see resources/views/components/admin-layout.blade.php
                cream: '#F5EDE3',
                surface: '#FBF5EC',
                line: '#ECE0D2',
                ink: { DEFAULT: '#271D19', soft: '#8A7A70' },
                dock: '#241C19',
                coral: { 100: '#FBE2D8', 300: '#F2AC97', 400: '#EC8F77', 500: '#E2765D', 700: '#C25D45' },
                good: { DEFAULT: '#5E8C6A', soft: '#E6EEE3' },
                warn: { DEFAULT: '#C98A2E', soft: '#F8ECD8' },
                bad: { DEFAULT: '#7A3144', soft: '#F2E2E3' },
            },
        },
    },

    plugins: [forms],
};
