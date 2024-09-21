import { Utils } from "../../framework/common/Uitls";

export default {
  checkFirst: () => window.CountUp !== undefined,
  local: {
    js: ["platform/modules/sokeio/count-up/dist/count-up.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    if (el.$sokeio_countup) return;
    let options = {};
    if (el.hasAttribute("wire:countup.options")) {
      options = new Function(
        `return ${el.getAttribute("wire:countup.options")};`
      )();
    }
    let valueNumber = el.getAttribute("wire:countup");
    if (!valueNumber || valueNumber == "") {
      valueNumber = Utils.dataGet(
        component.$wire,
        el.getAttribute("wire:countup.model")
      );
    }
    el.$sokeio_countup = new window.CountUp(el, valueNumber, options);
    if (!el.$sokeio_countup.error) {
      el.$sokeio_countup.start();
    } else {
      console.error(el.$sokeio_countup.error);
    }
  },
};
