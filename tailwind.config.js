import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
const colors = require('tailwindcss/colors')


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './vendor/filament/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/views/livewire/task-card-managment.blade.php"
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ...colors,
                danger: colors.rose,
                primary: {
                    50: "#FFFAE0",
                    100: "#FFF7C7",
                    200: "#FFEE8F",
                    300: "#FFE552",
                    400: "#FFDD1A",
                    500: "#E0BF00",
                    600: "#B39800",
                    700: "#857100",
                    800: "#5C4E00",
                    900: "#2E2700",
                    950: "#141100"
                },
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },

    plugins: [forms, typography],
};
