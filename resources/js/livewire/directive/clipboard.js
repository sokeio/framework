export default {
  checkFirst: () => window.Clipboard !== undefined,
  local: {
    js: ["/platform/modules/sokeio/clipboard/dist/clipboard.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    el.$sokeio_clipboard = new window.Clipboard(el, options);
  },
};
