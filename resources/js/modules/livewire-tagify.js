import { SokeioPlugin } from "../core/plugin";

export class LiveWireTagifyModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_TAGIFY_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive(
        "tagify",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0 || el.$wire_tagify) {
            return;
          }
          cleanup(() => {
            if (el?.$wire_tagify) {
              el.$wire_tagify?.destroy && el.$wire_tagify.destroy();
              el.$wire_tagify = null;
            }
            el.removeEventListener("input", onInput);
            el.removeEventListener("change", onChange);
          });
          let options = {};

          if (el.hasAttribute("wire:tagify-options")) {
            options = new Function(
              `return ${el.getAttribute("wire:tagify-options")};`
            )();
          }
          if (options.templates) {
            options.templates = new Function(`return ${options.templates};`)();
          }
          if (options.originalInputValueFormat) {
            options.originalInputValueFormat = new Function(
              `return ${options.originalInputValueFormat};`
            )();
          }
          if (options.validate) {
            options.validate = new Function(`return ${options.validate};`)();
          }
          if (options.transformTag) {
            options.transformTag = new Function(
              `return ${options.transformTag};`
            )();
          }
          if (options.callbacks) {
            options.callbacks = new Function(`return ${options.callbacks};`)();
          }
          if (options.hooks) {
            options.hooks = new Function(`return ${options.hooks};`)();
          }
          let modelKey = el.getAttribute("wire:model");
          const onInput = (e) => {
            if (!el.$wire_tagify) {
              return;
            }
            var value = e.detail.value;
            el.$wire_tagify.loading(true);
            component.$wire
              .callActionUI(options.whitelistAction, value)
              .then(function (rs) {
                el.$wire_tagify.whitelist = rs;
                el.$wire_tagify.loading(false).dropdown.show(value);
              });
          };
          const onChange = (e) => {
            self
              .getManager()
              .dataSet(component.$wire, modelKey, e.detail.value);
          };
          const tagifyCreate = () => {
            setTimeout(() => {
              if (!el.$wire_tagify) {
                el.$wire_tagify = new window.Tagify(el, options);
                if (options.whitelistAction) {
                  el.$wire_tagify.on("input", onInput);
                }
                el.$wire_tagify.on("change", onChange);
              }
            }, 50);
          };
          window.addStyleToWindow(
            self
              .getManager()
              .getUrlPublic("platform/modules/sokeio/tagify/dist/tagify.css"),
            function () {}
          );
          if (window.Tagify) {
            tagifyCreate();
          } else {
            window.addScriptToWindow(
              self
                .getManager()
                .getUrlPublic(
                  "platform/modules/sokeio/tagify/dist/tagify.min.js"
                ),
              function () {
                tagifyCreate();
              }
            );
          }
        }
      );
    }
  }
}
