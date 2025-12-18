import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // تفعيل Dark Mode باستخدام class
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
            colors: {
                primary: {
                    DEFAULT: 'rgb(92, 112, 224)',
                    50: 'rgb(239, 240, 251)',
                    100: 'rgb(223, 225, 247)',
                    200: 'rgb(191, 195, 239)',
                    300: 'rgb(159, 165, 231)',
                    400: 'rgb(127, 135, 223)',
                    500: 'rgb(92, 112, 224)',
                    600: 'rgb(74, 90, 179)',
                    700: 'rgb(55, 67, 134)',
                    800: 'rgb(37, 45, 90)',
                    900: 'rgb(18, 22, 45)',
                },
                secondary: {
                    DEFAULT: 'rgb(129, 95, 228)',
                    50: 'rgb(245, 241, 253)',
                    100: 'rgb(235, 227, 251)',
                    200: 'rgb(215, 199, 247)',
                    300: 'rgb(195, 171, 243)',
                    400: 'rgb(175, 143, 239)',
                    500: 'rgb(129, 95, 228)',
                    600: 'rgb(103, 76, 182)',
                    700: 'rgb(77, 57, 137)',
                    800: 'rgb(52, 38, 91)',
                    900: 'rgb(26, 19, 46)',
                },
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
