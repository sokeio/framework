export class BindFeature {
  $component;
  constructor($component) {
    this.$component = $component;
  }

  bindState(state, selectorOrEl) {
    this.$component.watch(state, () => {
      this.$component.queryAll(selectorOrEl).forEach((el) => {
        if (el.__binding === true) return;
        let start = el.selectionStart;
        el.value = this.$component.get(state);
        if (start) el.setSelectionRange(start, start);
      });
    });
    this.$component.on(
      ["change", "keypress"],
      (e) => {
        if (e.target.__timeout) {
          clearTimeout(e.target.__timeout);
          e.target.__binding = false;
        }
        e.target.__timeout = setTimeout(() => {
          if (e.target.__binding === true) return;
          e.target.__binding = true;
          this.$component.set(state, e.target.value);
          e.target.__binding = false;
          e.target.__binding = null;
        }, 100);
      },
      selectorOrEl
    );
  }
  bindText(state, selectorOrEl) {
    this.$component.watch(state, () => {
      this.$component.queryAll(selectorOrEl).forEach((el) => {
        el.innerText = this.$component.get(state);
      });
    });
    // set initial value
    // this.$component.set(state, this.$component.get(state));
  }
  bindHtml(state, selectorOrEl) {
    this.$component.watch(state, () => {
      this.$component.queryAll(selectorOrEl).forEach((el) => {
        el.innerHTML = this.$component.get(state);
      });
    });
  }
  run() {
    this.$component.queryAll("[s-model]").forEach((el) => {
      let state = el.getAttribute("s-model");
      this.bindState(state, el);
    });
    this.$component.queryAll("[s-text]").forEach((el) => {
      let state = el.getAttribute("s-text");
      this.bindText(state, el);
    });
    this.$component.queryAll("[s-html]").forEach((el) => {
      let state = el.getAttribute("s-html");
      this.bindHtml(state, el);
    });
  }
}
