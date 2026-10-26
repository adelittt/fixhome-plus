/** @type {import('tailwindcss').Config} */
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./app/Filament/**/*.php",
        "./app/Livewire/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
    darkMode: "class",
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
                fixhome: {
                    green: {
                        50: '#F2FAF5',
                        100: '#DFF2E6',
                        200: '#BFE3CC',
                        500: '#3FAE6D',
                        600: '#2E8B57',
                        700: '#1F6B3E',
                        900: '#14432A',
                        950: '#0F2A1A',
                    },
                    neutral: {
                        white: '#FFFFFF',
                        gray: '#5E6B60',
                        dark: '#1B2B20',
                        border: '#E3EBE5',
                    },
                    status: {
                        warning: '#E0A526',
                        danger: '#B3402F',
                        info: '#4A6B8A',
                    }
                }
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'btn': '12px',
                'card': '16px',
            },
            maxWidth: {
                'site': '1200px',
            },
            boxShadow: {
                'soft': '0 2px 10px rgba(15, 42, 26, 0.05)',
                'card': '0 4px 20px -2px rgba(15, 42, 26, 0.07)',
                'elevated': '0 12px 32px -4px rgba(15, 42, 26, 0.12)',
            },
            transitionDuration: {
                '150': '150ms',
                '200': '200ms',
                '250': '250ms',
            }
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
}
