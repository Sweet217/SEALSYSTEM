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
        base: 'https://pd.solytecmx.com/',
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            protocol: 'wss',
            host: 'https://pd.solytecmx.com/'
        },
    },
});
