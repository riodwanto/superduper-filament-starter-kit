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
        // Code splitting for better caching
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate vendor chunks for better caching
                    vendor: ['axios'],
                    // Separate Alpine.js if used
                    alpine: ['alpinejs'],
                },
                // Optimize chunk file names
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        // Enable CSS code splitting
        cssCodeSplit: true,
        // Optimize for production
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
        // Generate source maps for debugging (disable in production if needed)
        sourcemap: false,
        // Set chunk size warning limit
        chunkSizeWarningLimit: 1000,
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['axios'],
    },
    // CSS optimization
    css: {
        devSourcemap: process.env.NODE_ENV === 'development',
    },
    // Server configuration for development only
    server: process.env.NODE_ENV === 'development' ? {
        hmr: {
            host: 'localhost',
        },
    } : {},
});
