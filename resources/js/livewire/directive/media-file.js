import { Utils } from "../../framework/common/Uitls";

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
  init: ({ el, directive, component, cleanup, options }) => {
    let modelKey = el.getAttribute("wire:media-file");
    let multiple = el.getAttribute("multiple");
    let event = () => {
      window.showFileManager(
        function (file) {
          Utils.dataSet(component.$wire, modelKey, file);
        },
        "image",
        multiple == "true"
      );
    };
    el.addEventListener("click", event);
    cleanup(() => {
      el.removeEventListener("click", event);
    });
  },
};
