import { UI } from "./ui";

export class InputUI extends UI {
  getMakeEl() {
    return document.createElement("input");
  }
  init() {
    this.$el.type = "text";
    this.$el.className = "form-control";
    super.init();
    return this;
  }
}
