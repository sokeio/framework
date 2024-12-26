import manager from "../../media-storage";

export default {
  checkFirst: () => true,
  local: {
    js: [],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    window.sokeioUI.run(manager, { selector: el });
  },
};
