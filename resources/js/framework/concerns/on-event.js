import { Utils } from "../common/Uitls";
import BaseFeature from "./base";

class onEvent extends BaseFeature {
  constructor(component) {
    super(component);
  }
  filter(el) {
    return Array.from(el.attributes).some((attr) =>
      attr.name.startsWith("so-on:")
    );
  }
  applyItem(el) {
    Array.from(el.attributes).forEach((attr) => {
      if (attr.name.startsWith("so-on:")) {
        let eventName = attr.name.replace("so-on:", "");
        let variable = attr.value;
        el.addEventListener(eventName, (e) => {
          Utils.runFunction(variable, e, this.component);
        });
      }
    });
  }
}
export default onEvent;
