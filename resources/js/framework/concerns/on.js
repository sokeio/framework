import BaseFeature from "./base";

class onFeature extends BaseFeature {
  selector = "so-on\\:";
  constructor(component) {
    super(component);
  }

  applyItem(el) {
    console.log(el);
    return;
  
    if (variable) {
      this.component.watch(variable, () => {
        el.innerHTML = this.component[variable];
      });
      el.innerHTML = this.component[variable];
    }
  }
}
export default onFeature;
