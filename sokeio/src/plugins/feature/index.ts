import html from "./html";
import model from "./model";
import on from "./on";
import refs from "./refs";
import text from "./text";

const featureManager: any = {
  html,
  model,
  text,
  on,
  refs,
};
const prexSokeio = "so-";
const componentFeature = (el: any, component: any) => {
  [...el.attributes].forEach((attr) => {
    if (attr.name.startsWith(prexSokeio)) {
      let feature = attr.name.split(":");
      let name = feature[0].replace(prexSokeio, "");
      let method = feature[1];
      let method2 = "";
      if (method) {
        method = method.split(".");
        if (method.length > 1) {
          method2 = method[1];
          method = method[0];
        }
      }
      if (featureManager[name]) {
        featureManager[name]({
          component,
          el,
          name,
          method,
          method2,
          value: attr.value,
        });
      } else {
        console.error("feature not found: " + name);
      }
    }
  });
};
let excuteComponentFeature = function (component: any) {
  setTimeout(() => {
    if (!component.$el) return;
    component.$el.querySelectorAll("*").forEach((el: any) => {
      if (
        el.closest("[data-sokeio-id]").getAttribute("data-sokeio-id") !=
          component.getId() ||
        (el.hasAttribute("data-sokeio-id") &&
          el.getAttribute("data-sokeio-id") != component.getId())
      ) {
        return;
      }
      componentFeature(el, component);
    });
    componentFeature(component.$el, component);
  });
};

export default {
  js: [],
  css: [],
  check: function () {
    return true;
  },
  excute(component: any, event: any) {
    if (event !== "render") return;
    excuteComponentFeature(component);
  },
};
