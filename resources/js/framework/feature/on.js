import { Utils } from "../common/Uitls";

export default function ({ component, el, name, method, value }) {
  if (value) {
    el.addEventListener(method, (e) => {
      Utils.runFunction(value, e, component);
    });
  }
}
