export default {
  checkFirst: () => window.Clipboard !== undefined,
  local: {
    js: ["platform/modules/sokeio/clipboard/dist/clipboard.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    if (el.$sokeio_clipboard) return;
    let options = {};

    if (el.hasAttribute("wire:clipboard.options")) {
      options = new Function(
        `return ${el.getAttribute("wire:clipboard.options")};`
      )();
    }
    el.$sokeio_clipboard = new window.Clipboard(el, options);
  },
};
