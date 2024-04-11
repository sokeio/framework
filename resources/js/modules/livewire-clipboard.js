import { SokeioPlugin } from "../core/plugin";

export class LiveWireClipboardModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_CLIPBOARD_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("clipboard", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_clipboard) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:clipboard.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:clipboard.options")};`
          )();
        }
        const clipboardCreate = () => {
          if (el.$wire_clipboard) return;
          el.$wire_clipboard = new window.Clipboard(el, options);
        };
        if (window.Clipboard) {
          clipboardCreate();
        } else {
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/clipboard/dist/clipboard.min.js"
              ),
            function () {
              clipboardCreate();
            }
          );
        }
      });
    }
  }
}
