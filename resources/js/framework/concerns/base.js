class BaseFeature {
  selector = "";
  constructor(component) {
    this.component = component;
  }

  run() {
    this.component.el
      .querySelectorAll("[" + this.selector + "]")
      .forEach(this.applyItem.bind(this));
  }
  applyItem(el) {}
}
export default BaseFeature;
