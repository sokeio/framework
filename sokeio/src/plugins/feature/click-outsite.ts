import { executeFn } from "../../utils";

export default function ({ component, el, name: _name, method, value }: any) {
  if (value) {
    if (el["__SOKEIO_CLICK_OUTSITE"]) {
      el.removeEventListener(method, el["__SOKEIO_CLICK_OUTSITE"]);
    }
    el["__SOKEIO_CLICK_OUTSITE"] = (e: any) => {
      if (el.contains(e.target)) {
        return;
      }
      executeFn(value, e,e.target, component);
    };
    window.addEventListener("click", el["__SOKEIO_CLICK_OUTSITE"]);
    component.onDestroy(() => {
      window.removeEventListener("click", el["__SOKEIO_CLICK_OUTSITE"]);
    });
  }
}
