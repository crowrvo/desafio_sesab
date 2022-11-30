import { defineConfig } from "vite";
import AutoImport from "unplugin-auto-import/vite";
import AutoImportConfig from "./configs/auto-imports";
import AutoComponent from "unplugin-vue-components/vite";
import AutoComponentConfig from "./configs/auto-components";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: ` 
      @import "./src/assets/scss/index.scss"; 
      `,
      },
    },
  },
  plugins: [
    vue({
      reactivityTransform: true,
    }),
    AutoImport(AutoImportConfig),
    AutoComponent(AutoComponentConfig),
  ],
});
