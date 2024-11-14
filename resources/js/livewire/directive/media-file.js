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
    //wire:media-field
    let mediaField = el.getAttribute("wire:media-field");
    let event = () => {
      window.showFileManager(
        function (file) {
          let value = file;
          if (mediaField) {
            if (Array.isArray(value)) {
              value = value.map((item) => item[mediaField]);
            } else {
              value = value[mediaField];
            }
          }
          Utils.dataSet(component.$wire, modelKey, value);
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
