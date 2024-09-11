import BaseFeature from "./base";

class BindModel extends BaseFeature {
  selector = "so-model";

  applyItem(el) {
    let variable = el.getAttribute(this.selector);
    if (variable) {
      this.component.watch(variable, () => {
        el.value = this.component[variable];
      });
      el.value = this.component[variable];

      el.addEventListener("input", (e) => {
        this.component[variable] = e.target.value;
      });
    }
  }
}
export default BindModel;
