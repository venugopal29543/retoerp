import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/plot-layout.css',
                'resources/css/modals.css',
                'resources/css/forms.css',
                'resources/js/plot-manager.js',
                'resources/js/role-manager.js',
                'resources/js/modal-manager.js'
            ],
            refresh: true,
        }),
    ],
});
