import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/build', // Optional: Output directory for built assets
        base: 'https://caring-platypus-kind.ngrok-free.app/',  // Set base to '/https://caring-platypus-kind.ngrok-free.app/' for production
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'https://caring-platypus-kind.ngrok-free.app/',
            protocol: 'wss'
            // host: 'localhost'
        },
    },
});
