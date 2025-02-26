// vite.config.ts
import { defineConfig } from "file:///root/sites/ticketera/Hi.Events/frontend/node_modules/vite/dist/node/index.js";
import { lingui } from "file:///root/sites/ticketera/Hi.Events/frontend/node_modules/@lingui/vite-plugin/dist/index.cjs";
import react from "file:///root/sites/ticketera/Hi.Events/frontend/node_modules/@vitejs/plugin-react/dist/index.mjs";
import { copy } from "file:///root/sites/ticketera/Hi.Events/frontend/node_modules/vite-plugin-copy/dist/vite-plugin-copy.js";
var vite_config_default = defineConfig({
  server: {
    hmr: {
      port: 24678,
      protocol: "ws"
    }
  },
  plugins: [
    react({
      babel: {
        plugins: ["macros"]
      }
    }),
    lingui(),
    copy({
      targets: [{ src: "src/embed/widget.js", dest: "public" }],
      hook: "writeBundle"
    })
  ],
  define: {
    "process.env": process.env
  },
  ssr: {
    noExternal: ["react-helmet-async"]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcudHMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvcm9vdC9zaXRlcy90aWNrZXRlcmEvSGkuRXZlbnRzL2Zyb250ZW5kXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvcm9vdC9zaXRlcy90aWNrZXRlcmEvSGkuRXZlbnRzL2Zyb250ZW5kL3ZpdGUuY29uZmlnLnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9yb290L3NpdGVzL3RpY2tldGVyYS9IaS5FdmVudHMvZnJvbnRlbmQvdml0ZS5jb25maWcudHNcIjtpbXBvcnQge2RlZmluZUNvbmZpZ30gZnJvbSBcInZpdGVcIjtcbmltcG9ydCB7bGluZ3VpfSBmcm9tIFwiQGxpbmd1aS92aXRlLXBsdWdpblwiO1xuaW1wb3J0IHJlYWN0IGZyb20gXCJAdml0ZWpzL3BsdWdpbi1yZWFjdFwiO1xuaW1wb3J0IHtjb3B5fSBmcm9tIFwidml0ZS1wbHVnaW4tY29weVwiO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHNlcnZlcjoge1xuICAgICAgICBobXI6IHtcbiAgICAgICAgICAgIHBvcnQ6IDI0Njc4LFxuICAgICAgICAgICAgcHJvdG9jb2w6IFwid3NcIixcbiAgICAgICAgfSxcbiAgICB9LFxuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgcmVhY3Qoe1xuICAgICAgICAgICAgYmFiZWw6IHtcbiAgICAgICAgICAgICAgICBwbHVnaW5zOiBbXCJtYWNyb3NcIl0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9KSxcbiAgICAgICAgbGluZ3VpKCksXG4gICAgICAgIGNvcHkoe1xuICAgICAgICAgICAgdGFyZ2V0czogW3tzcmM6IFwic3JjL2VtYmVkL3dpZGdldC5qc1wiLCBkZXN0OiBcInB1YmxpY1wifV0sXG4gICAgICAgICAgICBob29rOiBcIndyaXRlQnVuZGxlXCIsXG4gICAgICAgIH0pLFxuICAgIF0sXG4gICAgZGVmaW5lOiB7XG4gICAgICAgIFwicHJvY2Vzcy5lbnZcIjogcHJvY2Vzcy5lbnYsXG4gICAgfSxcbiAgICBzc3I6IHtcbiAgICAgICAgbm9FeHRlcm5hbDogW1wicmVhY3QtaGVsbWV0LWFzeW5jXCJdLFxuICAgIH0sXG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBMFMsU0FBUSxvQkFBbUI7QUFDclUsU0FBUSxjQUFhO0FBQ3JCLE9BQU8sV0FBVztBQUNsQixTQUFRLFlBQVc7QUFFbkIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsUUFBUTtBQUFBLElBQ0osS0FBSztBQUFBLE1BQ0QsTUFBTTtBQUFBLE1BQ04sVUFBVTtBQUFBLElBQ2Q7QUFBQSxFQUNKO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxNQUFNO0FBQUEsTUFDRixPQUFPO0FBQUEsUUFDSCxTQUFTLENBQUMsUUFBUTtBQUFBLE1BQ3RCO0FBQUEsSUFDSixDQUFDO0FBQUEsSUFDRCxPQUFPO0FBQUEsSUFDUCxLQUFLO0FBQUEsTUFDRCxTQUFTLENBQUMsRUFBQyxLQUFLLHVCQUF1QixNQUFNLFNBQVEsQ0FBQztBQUFBLE1BQ3RELE1BQU07QUFBQSxJQUNWLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxRQUFRO0FBQUEsSUFDSixlQUFlLFFBQVE7QUFBQSxFQUMzQjtBQUFBLEVBQ0EsS0FBSztBQUFBLElBQ0QsWUFBWSxDQUFDLG9CQUFvQjtBQUFBLEVBQ3JDO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
