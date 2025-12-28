/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Deep Blue Color Palette theo prompt
                void: "#000814", // Background Body (Siêu tối)
                midnight: "#000022", // Background Sidebar
                surface: "#0d1b2a", // Background Cards/Post (Màu than xanh)
                "deep-navy": "#000055", // Primary Brand
                neon: "#00E5FF", // Accent/Highlight (Cyan)
                "text-main": "#FFFFFF",
                "text-muted": "#94a3b8",
                // Giữ lại primary/secondary để tương thích
                primary: {
                    DEFAULT: "#000055", // Deep Navy
                    dark: "#000022", // Midnight
                    light: "#0d1b2a", // Surface
                },
                secondary: {
                    DEFAULT: "#00E5FF", // Neon
                    dark: "#0099cc",
                },
            },
            fontFamily: {
                // Typography theo prompt
                display: [
                    "Rajdhani",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
                body: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
                sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
            },
            boxShadow: {
                neon: "0 0 10px rgba(0, 229, 255, 0.5), 0 0 20px rgba(0, 229, 255, 0.3)",
                "neon-lg":
                    "0 0 20px rgba(0, 229, 255, 0.6), 0 0 40px rgba(0, 229, 255, 0.4)",
            },
        },
    },
    plugins: [],
};
