export default {
  checkFirst: () => window.Coloris !== undefined,
  local: {
    js: ["/platform/module/sokeio/coloris/dist/coloris.min.js"],
    css: ["/platform/module/sokeio/coloris/dist/coloris.min.css"],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    Coloris({
      ...options,
      el: el,
    });
  },
};
