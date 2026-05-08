import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin/ginza-list.js',
                'resources/js/admin/all-store-list.js',
                'resources/css/admin.css',
                'resources/scss/application.scss',
            ],
            refresh: true,
        }),
        react(),
    ],
    optimizeDeps: {
        include: ['moment', 'jquery', 'daterangepicker'],
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        cors: true,
        strictPort: true,
        origin: 'http://localhost:5176',
        hmr: {
            host: 'localhost',
            port: 5173,
            clientPort: 5176,
            protocol: 'ws',
        },
        watch: {
            usePolling: true,
        },
    },
});
