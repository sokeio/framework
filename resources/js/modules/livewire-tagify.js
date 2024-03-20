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
          if (directive.modifiers.length > 0 || el.livewire____tagify) {
            return;
          }
          cleanup(() => {
            if (el.livewire____tagify) {
              el.livewire____tagify.destroy();
              el.livewire____tagify = null;
            }
            el.removeEventListener("input", onInput);
            el.removeEventListener("change", onChange);
            if (el.__tagify) {
              el.__tagify.destroy();
              el.__tagify = null;
            }
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
            var value = e.detail.value;
            el.livewire____tagify.loading(true);
            component.$wire.callActionUI(options.whitelistAction,value).then(function (rs) {
              el.livewire____tagify.whitelist = rs;
              el.livewire____tagify.loading(false).dropdown.show(value);
            });
          };
          const onChange = (e) => {
            self
              .getManager()
              .dataSet(component.$wire, modelKey, e.detail.value);
          };
          const tagifyCreate = () => {
            setTimeout(() => {
              if (!el.livewire____tagify) {
                el.livewire____tagify = new window.Tagify(el, options);
                if (options.whitelistAction) {
                  el.livewire____tagify.on("input", onInput);
                }
                el.livewire____tagify.on("change", onChange);
              }
            }, 50);
          };
          if (window.Tagify) {
            tagifyCreate();
          } else {
            window.addStyleToWindow(
              self
                .getManager()
                .getUrlPublic("platform/modules/sokeio/tagify/dist/tagify.css"),
              function () {}
            );
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
