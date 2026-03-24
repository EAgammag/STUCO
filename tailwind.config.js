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
                'inferno': '#a50104',       // Primary Action / Alerts
                'black-cherry': '#590004',  // Secondary / Deep Accents
                'mahogany': '#250001',      // Dark Mode Backgrounds / Text
                'platinum': '#f3f3f3',      // Main Background / Surface
            },
        },
    },

    plugins: [forms],
};
