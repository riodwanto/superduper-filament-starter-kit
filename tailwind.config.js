// import preset from "./vendor/filament/support/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */

export default {
    // presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#EEEEFB',
                    100: '#DCDCF7',
                    200: '#B9B8EF',
                    300: '#9795E6',
                    400: '#7471DE',
                    500: '#524ED5',
                    600: '#413CAD',
                    700: '#342F8A',
                    800: '#2D2B8D', // base primary
                    900: '#1E1C5A',
                },
                secondary: {
                    50: '#FFFBEB',
                    100: '#FFF6D6',
                    200: '#FFEDAD',
                    300: '#FFE585',
                    400: '#FFDC5C',
                    500: '#FFD333',
                    600: '#FFC903', // base secondary
                    700: '#E6B500',
                    800: '#BF9600',
                    900: '#997800',
                },
                background: {
                    white: '#FFFFFF',
                    wheat: '#F9F6F0',
                    light: '#F5F1E8',
                    subtle: '#EDE7DB',
                },
                success: '#10B981',
                error: '#EF4444',
                warning: '#F59E0B',
                info: '#3B82F6',
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
