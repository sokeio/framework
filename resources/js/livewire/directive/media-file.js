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
    let modelKey = el.getAttribute("wire:media-file.model");
    let event = () => {
      window.showFileManager(function (file) {
        Utils.dataSet(component.$wire, modelKey, file);
      });
    };
    el.addEventListener("click", event);
    cleanup(() => {
      el.removeEventListener("click", event);
    });
  },
};
