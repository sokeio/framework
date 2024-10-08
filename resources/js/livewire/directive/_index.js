import { Utils } from "../../framework/common/Uitls";
import apexcharts from "./apexcharts";
import carousel from "./carousel";
import clipboard from "./clipboard";
import countup from "./countup";
import flatpickr from "./flatpickr";
import getValue from "./get-value";
import masonry from "./masonry";
import modal from "./modal";
import qrcode from "./qrcode";
import sortable from "./sortable";
import sortableGroup from "./sortable-group";
import tagify from "./tagify";
import tinymce from "./tinymce";
const directive = {
  apexcharts,
  "get-value": getValue,
  qrcode,
  carousel,
  clipboard,
  countup,
  flatpickr,
  masonry,
  sortable,
  "sortable-group": sortableGroup,
  tagify,
  tinymce,
  modal,
};

let waitLoader = (setting, items, level) => {
  if (!setting.checkFirst || setting.checkFirst()) {
    setting.init(items);
    return;
  }
  if (level > 50) {
    return;
  }
  setTimeout(() => {
    waitLoader(setting, items, level + 1);
  }, 50);
};
function install(app) {
  Object.keys(directive).forEach(function (key) {
    let setting = directive[key];
    ///{ el, directive, component }
    app.directive(key, (items) => {
      if (items.length === 0) return;
      // Only fire this handler on the "root" directive.
      if (
        items.directive.modifiers.length > 0 ||
        items.el[
          `$$sokeio_${key
            .replace(/\./g, "_")
            .replace(/\//g, "_")
            .replace(/-/g, "_")}`
        ]
      ) {
        return;
      }
      let options = {};
      if ((options = items.el.getAttribute(`wire:${key}.options`))) {
        options = new Function(`return ${options};`)();
      }
      if (setting.checkFirst && !setting.checkFirst()) {
        if (
          setting?.cdn?.js &&
          Array.isArray(setting?.cdn?.js) &&
          setting?.cdn?.js.length > 0
        ) {
          Utils.addScriptToWindow(setting?.cdn?.js);
        } else if (setting?.local?.js) {
          Utils.addScriptToWindow(setting?.local?.js);
        }
        if (
          setting?.cdn?.css &&
          Array.isArray(setting?.cdn?.css) &&
          setting?.cdn?.css.length > 0
        ) {
          Utils.addStyleToWindow(setting?.cdn?.css);
        } else if (setting?.local?.css) {
          Utils.addStyleToWindow(setting?.local?.css);
        }
      }
      waitLoader(setting, { ...items, options }, 0);
    });
  });
}
export default { directive, install };
