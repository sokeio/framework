export class OnFeature {
  $component;
  constructor($component) {
    this.$component = $component;
  }
  onClick() {
    this.$component.queryAll("[s-on\\:click]").forEach((el) => {
      this.$component.on(el, "click", () => {
        new Function(`return ${el.getAttribute("s-on:click")};`).bind(
          this.$component
        )();
      });
    });
  }
  onEnter() {
    this.$component.queryAll("[s-on\\:enter]").forEach((el) => {
      this.$component.on(el, "keyup", (e) => {
        if (e.keyCode === 13) {
          new Function(`return ${el.getAttribute("s-on:enter")};`).bind(
            this.$component
          )();
        }
      });
    });
  }
  run() {
    this.onClick();
    this.onEnter();
  }
}
