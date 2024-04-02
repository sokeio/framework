import { MakeObject } from "./object";
import { UI } from "./ui";

export class InputUI extends UI {
  makeEl() {
    let el = document.createElement("input");
    el.___ui = this;
    this.$el = el;
    this.$el.type = "text";
    this.$el.className = "form-control";
  }
}
