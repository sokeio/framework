export class OnFeature {
  $component;
  constructor($component) {
    this.$component = $component;
  }
  callFunction($func) {
    try {
      new Function($func).apply(this.$component);
    } catch (e) {}
  }
  onClick() {
    this.$component.queryAll("[s-on\\:click]").forEach((el) => {
      this.$component.on(
        "click",
        () => {
          this.callFunction(el.getAttribute("s-on:click"));
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
            this.callFunction(el.getAttribute("s-on:enter"));
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
