export default {
  checkFirst: () => window.ApexCharts !== undefined,
  local: {
    js: ["/platform/module/sokeio/apexcharts/dist/apexcharts.min.js"],
    css: ["/platform/module/sokeio/apexcharts/dist/apexcharts.css"],
  },
  cdn: {
    js: [
      "https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js",
    ],
    css: ["https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css"],
  },
  init: ({ el, directive, component, cleanup }) => {},
};
