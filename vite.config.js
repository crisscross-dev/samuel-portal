import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/css/index.css',
                'resources/css/login.css',
                'resources/css/shared/base.css',

                'resources/js/app.js', 
                'resources/js/login.js', 
                'resources/js/index.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
