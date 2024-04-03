export class OnFeature {
  $component;
  constructor($component) {
    this.$component = $component;
  }
  run() {
    this.$component.queryAll("[s-on\\:click]").forEach((el) => {
      this.$component.on(el, "click", () => {
        new Function(`return ${el.getAttribute("s-on:click")};`).bind(
          this.$component
        )();
      });
    });
  }
}
