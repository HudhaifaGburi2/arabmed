import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/main-admin.js',
                'resources/js/student/main-student.js',
                'resources/js/landing/main-welcome.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
