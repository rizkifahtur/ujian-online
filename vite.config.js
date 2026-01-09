import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
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
    chunkSizeWarningLimit: 1000,
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ['vue', '@inertiajs/vue3'],
          sweetalert: ['sweetalert2'],
          tinymce: ['@tinymce/tinymce-vue'],
          utils: ['moment', 'xlsx', '@vuepic/vue-datepicker', '@chenfengyuan/vue-countdown'],
        },
      },
    },
  },
});
