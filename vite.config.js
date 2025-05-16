import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
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
        host: true,
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
});
