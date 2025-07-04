import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Roboto", ...defaultTheme.fontFamily.sans],
            },
            animation: {
                "gradient-border": "gradient-border 4s ease-in-out infinite",
                "border-glow": "border-glow 3s ease-in-out infinite",
                "table-shadow": "table-shadow 4s ease-in-out infinite",
                "shimmer": "shimmer 3s ease-in-out infinite",
                "shimmer-delay-1": "shimmer 3s ease-in-out infinite 0.5s",
                "shimmer-delay-2": "shimmer 3s ease-in-out infinite 1s",
                "shimmer-delay-3": "shimmer 3s ease-in-out infinite 1.5s",
                "shimmer-delay-4": "shimmer 3s ease-in-out infinite 2s",
                "shimmer-delay-5": "shimmer 3s ease-in-out infinite 2.5s"
            },
            backgroundSize: {
                "300": "300% 300%"
            }
        },
    },

    plugins: [forms, typography],
};
