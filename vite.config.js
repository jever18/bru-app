import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~': path.resolve(__dirname, 'node_modules'),
        }
    },
    // *** TAMBAHKAN BLOK INI ***
    css: {
        preprocessorOptions: {
            scss: {
                // Menyembunyikan peringatan dari file-file di node_modules
                quietDeps: true,
            }
        }
    }
    // *************************
});
