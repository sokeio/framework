export default {
  checkFirst: () => window.Plyr !== undefined,
  local: {
    js: ["/platform/module/sokeio/plyr/plyr.min.js"],
    css: ["/platform/module/sokeio/plyr/plyr.css"],
  },
  cdn: {
    js: ["https://cdn.jsdelivr.net/npm/plyr@latest/dist/plyr.min.js"],
    css: ["https://cdn.jsdelivr.net/npm/plyr@latest/dist/plyr.css"],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    el.$sokeio_plyr = new window.Plyr(el, options);
  },
};
