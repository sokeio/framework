export default {
  checkFirst: () => window.bootstrap.Carousel !== undefined,
  local: {
    js: [],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    el.$sokeio_carousel = window.bootstrap.Carousel.getOrCreateInstance(
      el,
      options
    );
  },
};
