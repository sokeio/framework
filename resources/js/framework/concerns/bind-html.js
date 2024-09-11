import BaseFeature from "./base";

class BindHtml extends BaseFeature {
  selector = "so-html";

  applyItem(el) {
    let variable = el.getAttribute(this.selector);
    if (variable) {
      this.component.watch(variable, () => {
        el.innerHTML = this.component[variable];
      });
      el.innerHTML = this.component[variable];
    }
  }
}
export default BindHtml;
