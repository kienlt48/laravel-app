import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // Ép chạy 127.0.0.1 để tránh lỗi [::1]
        host: '127.0.0.1',
        hmr: {
            host: '127.0.0.1',
        },
    },
});
