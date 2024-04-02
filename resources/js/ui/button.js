import { MakeObject } from "./object";
import { UI } from "./ui";

export class ButtonUI extends UI {
  constructor($parent) {
    super($parent, document.createElement("button"));
    this.$el.type = "button";
    this.$el.className = "btn";
  }
  label = "";
  setLabel(label) {
    this.label = label;
    return this;
  }

  afterRender() {
    this.html(this.label);
    return this;
  }
  static make($text) {
    return super.make().setLabel($text);
  }
}
