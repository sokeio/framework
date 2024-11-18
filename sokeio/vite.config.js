import { resolve } from "path";
import { defineConfig } from "vite";

export default defineConfig({
  build: {
    lib: {
      entry: [resolve(__dirname, "src/main.ts")],
      name: "sokeio",
      fileName: (format, name) => {
        if (format === "es") {
          return `sokeio.js`;
        }
        return `sokeio.${format}.js`;
      },
    },
    rollupOptions: {
      external: [],
    },
  },
});
