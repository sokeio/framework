import { BytePlugin } from "../core/plugin";

export class LiveWireFlatpickrModule extends BytePlugin {
  getKey() {
    return "BYTE_LIVEWIRE_FLATPICK_MODULE";
  }
  booting() {
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
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/byte/flatpickr/dist/flatpickr.min.css"
              ),
            function () {}
          );
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/byte/flatpickr/dist/flatpickr.min.js"
              ),
            function () {
              flatpickrCreate();
            }
          );
        }
      });
    }
  }
}
