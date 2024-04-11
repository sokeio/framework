import { SokeioPlugin } from "../core/plugin";

export class LiveWireCountUpModule extends SokeioPlugin {
  getKey(){
    return 'SOKEIO_LIVEWIRE_COUNTUP_MODULE';
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("countup", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_countup) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:countup.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:countup.options")};`
          )();
        }
        let valueNumber = el.getAttribute("wire:countup");
        if (!valueNumber || valueNumber == "") {
          valueNumber = self.getManager().dataGet(
            component.$wire,
            el.getAttribute("wire:countup.model")
          );
        }
        const countupCreate = () => {
          if (el.$wire_countup) return;
          el.$wire_countup = new window.CountUp(el, valueNumber, options);
          if (!el.$wire_countup.error) {
            el.$wire_countup.start();
          } else {
            console.error(el.$wire_countup.error);
          }
        };
        if (window.CountUp) {
          countupCreate();
        } else {
          window.addScriptToWindow(
            self.getManager().getUrlPublic(
              "platform/modules/sokeio/count-up/dist/count-up.min.js"
            ),
            function () {
              countupCreate();
            }
          );
        }
      });
    }
  }
}
