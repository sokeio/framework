import { UI } from "./ui";

export class ButtonUI extends UI {
  getMakeEl() {
    return document.createElement("button");
  }
  init() {
    this.$el.type = "button";
    this.$el.className = "btn";
    super.init();
    return this;
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
