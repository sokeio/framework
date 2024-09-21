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
  init: ({ el, directive, component, cleanup }) => {
    if (el.$sokeio_carousel) return;
    let options = {};

    if (el.hasAttribute("wire:carousel.options")) {
      options = new Function(
        `return ${el.getAttribute("wire:carousel.options")};`
      )();
    }
    el.$sokeio_carousel = window.bootstrap.Carousel.getOrCreateInstance(
      el,
      options
    );
  },
};
