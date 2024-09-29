import { Utils } from "../framework/common/Uitls";
import directive from "./directive";
let waitLoader = (setting, items, level) => {
  if (!setting.checkFirst || setting.checkFirst()) {
    setting.init(items);
    return;
  }
  if (level > 20) {
    return;
  }
  setTimeout(() => {
    waitLoader(setting, items, level + 1);
  }, 50);
};
document.addEventListener("livewire:init", () => {
  Object.keys(directive).forEach(function (key) {
    let setting = directive[key];
    ///{ el, directive, component }
    window.Livewire.directive(key, (items) => {
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
      if (items.el.hasAttribute(`wire:${key}.options`)) {
        options = new Function(
          `return ${items.el.getAttribute(`wire:${key}.options`)};`
        )();
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
});
