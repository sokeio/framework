import BaseFeature from "./base";

class BindText extends BaseFeature {
  selector = "so-text";
  constructor(component) {
    super(component);
  }

  applyItem(el) {
    let variable = el.getAttribute(this.selector);
    if (variable) {
      this.component.watch(variable, () => {
        el.innerText = this.component[variable];
      });
      el.innerText = this.component[variable];
    }
  }
}
export default BindText;
