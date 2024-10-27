export default {
  checkFirst: () => window.Masonry !== undefined,
  local: {
    js: ["/platform/module/sokeio/masonry/dist/masonry.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup ,options}) => {
    el.$sokeio_masonry = new Masonry(el, options);
    let relayout = () => {
      if (el.$sokeio_masonry_timer) {
        el.$sokeio_masonry.layout();
        clearTimeout(el.$sokeio_masonry_timer);
        el.$sokeio_masonry_timer = null;
      }
      el.$sokeio_masonry_timer = setTimeout(() => {
        el.$sokeio_masonry.layout();
        el.$sokeio_masonry_timer = null;
      }, 100);
    };
    window.addEventListener("resize", relayout);
    cleanup(() => {
      window.removeEventListener("resize", relayout);
    });
  },
};
