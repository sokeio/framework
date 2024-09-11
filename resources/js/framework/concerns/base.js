class BaseFeature {
  selector = "*";
  constructor(component) {
    this.component = component;
  }

  run() {
    let selector = this.selector;
    if (selector != "*") {
      selector = "[" + this.selector + "]";
    }
    [...this.component.el.querySelectorAll(selector)]
      .filter(this.filter.bind(this))
      .forEach(this.applyItem.bind(this));
  }
  filter(el) {
    return true;
  }
  applyItem(el) {}
}
export default BaseFeature;
