import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { createHtmlPlugin } from 'vite-plugin-html';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    createHtmlPlugin({
      inject: {
        injectData: {
          appUrl: process.env.APP_URL,
        },
      },
    }),
  ],
  build: {
    outDir: 'public/build',  // Spécifie le répertoire de sortie
    manifest: true,  // Cela génère le fichier manifest.json
  },
  server: {
    proxy: {
      '/app': 'http://localhost',
    },
  },
});
