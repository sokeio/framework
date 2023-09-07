export class LiveWireFlatpickrModule {
  manager = undefined;
  init() {}
  loading() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("flatpickr", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.livewire____flatpickr) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:flatpickr.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:flatpickr.options")};`
          )();
        }
        const flatpickrCreate = () => {
          if (el.livewire____flatpickr) return;
          el.livewire____flatpickr = new window.flatpickr(el, options);
        };
        if (window.flatpickr) {
          flatpickrCreate();
        } else {
          window.addStyleToWindow(
            self.manager.getUrlPublic(
              "byteplatform/modules/byteplatform/flatpickr/dist/flatpickr.min.css"
            ),
            function () {}
          );
          window.addScriptToWindow(
            self.manager.getUrlPublic(
              "byteplatform/modules/byteplatform/flatpickr/dist/flatpickr.min.js"
            ),
            function () {
              flatpickrCreate();
            }
          );
        }
      });
    }
  }
  unint() {}
}
