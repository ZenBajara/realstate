import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';
import path from 'path';

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
        '@': '/resources/js',
        '@assets': path.resolve(__dirname,'resources/assets')
        },
    },
  css: {
    postcss: {
        plugins: [tailwindcss()],
    },
  },
});
