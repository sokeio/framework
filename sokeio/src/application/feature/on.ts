import { executeFn } from "../utils";

export default function ({ component, el, name: _name, method, value }: any) {
  if (value) {
    if (el["__SOKEIO_ON__" + method]) {
      el.removeEventListener(method, el["__SOKEIO_ON__" + method]);
    }
    el["__SOKEIO_ON__" + method] = (e: any) => {
      let ignore: any = el.getAttribute("so-on:ignore");
      if (ignore) {
        if (e.target.closest(ignore)) return;
      }
      executeFn(value, e, component);
    };
    el.addEventListener(method, el["__SOKEIO_ON__" + method]);
  }
}
