import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],

    safelist: [
        'grid',
        'grid-cols-1',
        'grid-cols-2',
        'grid-cols-3',
        'grid-cols-4',
        'sm:grid-cols-1',
        'sm:grid-cols-2',
        'sm:grid-cols-3',
        'sm:grid-cols-4',
        'md:grid-cols-1',
        'md:grid-cols-2',
        'md:grid-cols-3',
        'md:grid-cols-4',
        'lg:grid-cols-1',
        'lg:grid-cols-2',
        'lg:grid-cols-3',
        'lg:grid-cols-4',
        'xl:grid-cols-1',
        'xl:grid-cols-2',
        'xl:grid-cols-3',
        'xl:grid-cols-4',
        'bg-gradient-to-r',
        'from-gray-700',
        'to-gray-600',
    ],
};
