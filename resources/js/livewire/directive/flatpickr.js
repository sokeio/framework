import { dataSet } from "../../utils";
import { convertDateTimeFormatToMask } from "../util";

export default {
  checkFirst: () => window.flatpickr !== undefined,
  local: {
    js: ["/platform/module/sokeio/flatpickr/dist/flatpickr.min.js"],
    css: ["/platform/module/sokeio/flatpickr/dist/flatpickr.min.css"],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    let modelKey = el.getAttribute("wire:model");
    let dateFormat =
      options?.dateFormat ?? (options?.enableTime ? "Y/m/d H:i:S" : "Y/m/d");
    let maskFormat = convertDateTimeFormatToMask(dateFormat);
    el.$sokeio_flatpickr = new window.flatpickr(el, {
      allowInput: true,
      allowInvalidPreload: true,
      dateFormat,
      ...options,
      onChange: (selectedDates, dateStr, instance) => {
        dataSet(component.$wire, modelKey, selectedDates);
      },
    });
    setTimeout(async () => {
      Alpine.$data(el).maskFormat = maskFormat;
    }, 10);
  },
};
