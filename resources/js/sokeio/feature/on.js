export class OnFeature {
  $component;
  constructor($component) {
    this.$component = $component;
  }
  onClick() {
    this.$component.queryAll("[s-on\\:click]").forEach((el) => {
      this.$component.on(
        "click",
        () => {
          new Function(`return ${el.getAttribute("s-on:click")};`).bind(
            this.$component
          )();
        },
        el
      );
    });
  }
  onEnter() {
    this.$component.queryAll("[s-on\\:enter]").forEach((el) => {
      this.$component.on(
        "keyup",
        (e) => {
          if (e.keyCode === 13) {
            new Function(`return ${el.getAttribute("s-on:enter")};`).bind(
              this.$component
            )();
          }
        },
        el
      );
    });
  }
  run() {
    this.onClick();
    this.onEnter();
  }
}
