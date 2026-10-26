/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");
const defaultTheme = require("tailwindcss/defaultTheme");
import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

function withOpacityValue(variable) {
    return ({ opacityValue }) => {
        if (opacityValue === undefined) {
            return `rgb(var(${variable}))`;
        }
        return `rgb(var(${variable}) / ${opacityValue})`;
    };
}

export default {
    content: [
        "./resources/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/awcodes/overlook/resources/**/*.blade.php",
        "./vendor/andrewdwallo/filament-selectify/resources/views/**/*.blade.php",
        "<./vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php",
        "./vendor/diogogpinto/filament-auth-ui-enhancer/resources/**/*.blade.php",
    ],
    darkMode: "class",
    presets: [preset],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#F2FAF5',
                    100: '#DFF2E6',
                    200: '#BFE3CC',
                    300: '#85C99E',
                    400: '#5DBA81',
                    500: '#3FAE6D',
                    600: '#2E8B57',
                    700: '#1F6B3E',
                    800: '#175430',
                    900: '#14432A',
                    950: '#0F2A1A',
                },
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
}
