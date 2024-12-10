import icon from "../../icon";
import { dataGet, dataSet } from "../../utils";

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
    let modelKey = el.getAttribute("wire:media-icon");
    let event = () => {
      window.showModal("Icon Manager", {
        fnCallback: function (value) {
          dataSet(component.$wire, modelKey, value);
        },
        template: icon,
        modalSize: "xxl",
        skipOverlayClose: true,
        iconValue: dataGet(component.$wire, modelKey),
      });
    };
    el.addEventListener("click", event);
    cleanup(() => {
      el.removeEventListener("click", event);
    });
  },
};
