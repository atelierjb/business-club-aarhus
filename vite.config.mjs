import { defineConfig } from "vite"
import tailwindcss from "@tailwindcss/vite"
import basicSsl from "@vitejs/plugin-basic-ssl"
import path from "path"

export default defineConfig(({ command }) => {
  const isBuild = command === "build"

  return {
    base: isBuild ? "/wp-content/themes/business-club-aarhus/dist/" : "/",
    server: {
      port: 3000,
      cors: true,
      origin: "https://bcaa.test",
      https: true,
      hmr: {
        overlay: false, // Disable error overlay to prevent conflicts
      },
    },
    build: {
      manifest: true,
      outDir: "dist",
      rollupOptions: {
        input: [
          "resources/js/app.js",
          "resources/css/app.css",
          "resources/css/editor-style.css",
        ],
      },
    },
    resolve: {
      alias: {
        "~fonts": isBuild
          ? path.resolve(__dirname, "resources/fonts")
          : path.resolve(
              __dirname,
              "wp-content/themes/business-club-aarhus/resources/fonts"
            ),
      },
    },
    plugins: [tailwindcss(), basicSsl()],
    optimizeDeps: {
      include: ["motion", "lenis"],
    },
    define: {
      // Prevent global variable conflicts
      global: "globalThis",
    },
  }
})
