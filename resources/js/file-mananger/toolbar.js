import { UI } from "../ui";

export class Folder extends UI {
  setLabel(label) {
    this.$el.innerHTML = label;
    return this;
  }
}
