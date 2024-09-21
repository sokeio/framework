export default {
  checkFirst: () => window.Masonry !== undefined,
  local: {
    js: ["platform/modules/sokeio/masonry/dist/masonry.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    if (directive.modifiers.length > 0 || el.$sokeio_masonry) {
      el.$sokeio_masonry?.layout();
      return;
    }
    let options = {};
    if (el.hasAttribute("wire:masonry.options")) {
      options = new Function(
        `return ${el.getAttribute("wire:masonry.options")};`
      )();
    }
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
