import { fileURLToPath, URL } from 'node:url'
import VitePluginHtmlEnv from 'vite-plugin-html-env'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

export default ({ mode }: {mode: any}) => {
  // Load app-level env vars to node-level env vars.
  process.env = {...process.env, ...loadEnv(mode, process.cwd())};

  return  defineConfig({
  plugins: [
    vue(),
    VitePluginHtmlEnv({
      prefix: '{{',
      suffix: '}}',
      envPrefixes: ['VITE_']
    }),
  ],
  build: {
    rollupOptions: {
      output: {
        assetFileNames: `skins/standard/assets/[name]-[hash][extname]`,
        chunkFileNames: 'skins/standard/assets/js/[name]-[hash].js',
        entryFileNames: 'skins/standard/assets/js/[name]-[hash].js',
      },
    },
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})};
