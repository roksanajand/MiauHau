import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/css/animal_form.css','resources/css/animal-history.css','resources/css/admin.css'],
            refresh: true,
        }),
    ],
});
