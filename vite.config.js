import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/admin/theme.css",
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
                manualChunks: {
                    vendor: ['axios'],
                },
                // Cache-busting hashed filenames for production assets
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        cssCodeSplit: true,  // Split CSS for better caching
        minify: 'terser',   // Minify JavaScript in production
        terserOptions: {
            compress: {
                drop_console: true,  // Remove console logs in production
                drop_debugger: true, // Remove debugger statements
            },
        },
        sourcemap: false,           // Disable source maps in production
        chunkSizeWarningLimit: 1000, // Warn if chunks exceed 1MB
    },
    optimizeDeps: {
        include: ['axios'],  // Pre-bundle axios for faster development
    },
    css: {
        devSourcemap: process.env.NODE_ENV === 'development',  // Enable sourcemaps in development
    },
});
