import { SokeioPlugin } from "../core/plugin";
import { convertDateTimeFormatToMask } from "../utils";

export class LiveWireFlatpickrModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_FLATPICK_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("flatpickr", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_flatpickr) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:flatpickr.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:flatpickr.options")};`
          )();
        }

        let modelKey = el.getAttribute("wire:model");
        let dateFormat =
          options.dateFormat ?? (options.enableTime ? "Y/m/d H:i:S" : "Y/m/d");
        let maskFormat = convertDateTimeFormatToMask(dateFormat);
        const flatpickrCreate = async () => {
          if (el.$wire_flatpickr) return;
          el.$wire_flatpickr = new window.flatpickr(el, {
            allowInput: true,
            allowInvalidPreload: true,
            dateFormat,
            ...options,
            onChange: (selectedDates, dateStr, instance) => {
              self
                .getManager()
                .dataSet(component.$wire, modelKey, selectedDates);
            },
          });
          setTimeout(async () => {
            // el.$wire_flatpickr.setDate(
            //   await self.getManager().dataGet(component.$wire, modelKey)
            // );
            Alpine.$data(el).maskFormat = maskFormat;
          }, 10);
        };
        window.addStyleToWindow(
          self
            .getManager()
            .getUrlPublic(
              "platform/modules/sokeio/flatpickr/dist/flatpickr.min.css"
            ),
          function () {}
        );
        if (window.flatpickr) {
          flatpickrCreate();
        } else {
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/flatpickr/dist/flatpickr.min.js"
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
