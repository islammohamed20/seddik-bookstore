import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'primary-yellow': '#FFD700',
                'primary-yellow-dark': '#FFC43F',
                'primary-blue': '#003399',
                'primary-blue-dark': '#003D7A',
                'primary-red': '#FF3333',
                'primary-red-dark': '#E53935',
            },
            fontFamily: {
                sans: ['Cairo', 'Tajawal', 'Figtree', ...defaultTheme.fontFamily.sans],
                cairo: ['Cairo', 'sans-serif'],
                tajawal: ['Tajawal', 'sans-serif'],
                poppins: ['Poppins', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
            },
            borderRadius: {
                'sm-custom': '8px',
                'md-custom': '12px',
                'lg-custom': '16px',
            },
            boxShadow: {
                'custom': '0 2px 8px rgba(0,0,0,0.1)',
            },
        },
    },

    plugins: [forms],
};
