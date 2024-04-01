import { UI } from "./ui";

export class Input extends UI {
  constructor($parent) {
    super($parent, document.createElement("input"));
    this.$el.type = "text";
    this.$el.className = "form-control";
  }
  static Make() {
    return new Input(null);
  }
}
