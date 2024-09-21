import { Utils } from "../common/Uitls";

export default function ({ component, el, name, method, value }) {
  if (value) {
    el.addEventListener(method, (e) => {
      if ((ignore = el.getAttribute("so-on:ignore"))) {
        if (e.target.closest(ignore)) return;
      }
      Utils.runFunction(value, e, component);
    });
  }
}
