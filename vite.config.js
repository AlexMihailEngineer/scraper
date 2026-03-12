import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Essential for Docker: Listen on all network interfaces
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // Tells the browser how to find the Hot Module Replacement server
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true, // Recommended for external HDDs/Network drives
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
