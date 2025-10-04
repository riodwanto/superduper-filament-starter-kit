import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/admin/theme.css",

                // Themes
                // ....
            ],
            refresh: [
                ...refreshPaths,
                "app/Livewire/**"
            ],
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    
                    if (ext === 'css') {
                        return 'css/[name]-[hash].[ext]';
                    }
                    
                    if (['woff', 'woff2', 'ttf', 'eot'].includes(ext)) {
                        return 'fonts/[name]-[hash].[ext]';
                    }
                    
                    if (['svg', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico'].includes(ext)) {
                        return 'images/[name]-[hash].[ext]';
                    }
                    
                    return 'assets/[name]-[hash].[ext]';
                },
            },
        },
        cssCodeSplit: true,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production',
            },
        },
        sourcemap: process.env.NODE_ENV !== 'production',
        chunkSizeWarningLimit: 600,
    },
    optimizeDeps: {
        include: ['axios'],
    },
    css: {
        devSourcemap: process.env.NODE_ENV !== 'production',
    },
});