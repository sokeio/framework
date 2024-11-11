import { Utils } from "../common/Uitls";

export default function ({ component, el, name, method, value }) {
  if (value) {
    if (el["__SOKEIO_ON__" + method]) {

      el.removeEventListener(method, el["__SOKEIO_ON__" + method]);
    }
    el["__SOKEIO_ON__" + method] = (e) => {
      console.log(component);
      if ((ignore = el.getAttribute("so-on:ignore"))) {
        if (e.target.closest(ignore)) return;
      }
      Utils.runFunction(value, e, component);
    };
    el.addEventListener(method, el["__SOKEIO_ON__" + method]);
  }
}
