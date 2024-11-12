import manager from "../../file-mananger/component/manager";

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
