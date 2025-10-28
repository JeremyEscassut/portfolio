import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // When the site is deployed to a subdirectory (example: https://.../portfolio/)
    // we must set the base so Vite generates correct absolute URLs in the
    // production build. Alwaysdata deploys this project under `/portfolio/`.
    // If you later deploy to root, change this to '/' or make it environment-driven.
    base: '/portfolio/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
