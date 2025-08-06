import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'public/assets/scss/style.scss'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@scss': path.resolve(__dirname, 'public/assets/scss/app'),  // Define an alias for SCSS folder
        },
    },
    build: {
        rollupOptions: {
            external: ['laravel-echo', 'pusher-js'],
        },
    },
    define: {
        'process.env': {
            MIX_REVERB_APP_KEY: JSON.stringify(process.env.MIX_REVERB_APP_KEY || 'slamin'),
            MIX_REVERB_HOST: JSON.stringify(process.env.MIX_REVERB_HOST || 'localhost'),
            MIX_REVERB_PORT: JSON.stringify(process.env.MIX_REVERB_PORT || '8080'),
        }
    }
});
