import { Utils } from "../framework/common/Uitls";
import directive from "./directive";

document.addEventListener("livewire:init", () => {
  Object.keys(directive).forEach(function (key) {
    let setting = directive[key];
    ///{ el, directive, component }
    window.Livewire.directive(key, (items) => {
      if (setting.checkFirst && !setting.checkFirst()) {
        if (setting?.cdn?.js) {
          Utils.addScriptToWindow(setting?.cdn?.js);
        } else if (setting?.local?.js) {
          Utils.addScriptToWindow(setting?.local?.js);
        }
        if (setting?.cdn?.css) {
          Utils.addStyleToWindow(setting?.cdn?.css);
        } else if (setting?.local?.css) {
          Utils.addStyleToWindow(setting?.local?.css);
        }
        return;
      }
      setTimeout(() => {
        setting.init(items);
      }, 0);
    });
  });
});
