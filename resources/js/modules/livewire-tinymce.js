import { SokeioPlugin } from "../core/plugin";
import { checkShortcode } from "../utils";

export class LiveWireTinymceModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_TINYMCE_MODULE";
  }
  booting() {
    const self = this;
    if (window.Livewire) {
      window.Livewire.directive(
        "tinymce",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0 || el.$wire_tinymce) {
            return;
          }
          let options = {};
          if (el.hasAttribute("wire:tinymce")) {
            options = new Function(
              `return ${el.getAttribute("wire:tinymce")};`
            )();
          }
          cleanup(() => {
            if (el.$wire_tinymce && el.$wire_tinymce.remove) {
              el.$wire_tinymce.remove();
              el.$wire_tinymce = null;
            }
          });
          let modelKey =
            el.getAttribute("wire:tinymce-model") ??
            el.getAttribute("wire:model");
          const tinymceInit = () => {
            if (el.$wire_tinymce) return;
            el.$wire_tinymce = window.tinymce.init({
              ...(self.getManager().$config["tinyecm_option"] ?? {}),
              ...options,
              promotion: false,
              target: el,
              setup: function (editor) {
                editor.on("init", function () {
                  editor.setContent(el.value);
                  editor.undoManager.dispatchChange();
                });
                editor.on("blur", function (e) {
                  let html = editor.getContent();
                  if (window.removeHighlightShortcodes) {
                    html = window.removeHighlightShortcodes(html);
                  }
                  let elFix = document.createElement("div");
                  elFix.innerHTML = html;
                  elFix.querySelectorAll("p").forEach((elP) => {
                    if (checkShortcode(elP.innerText)) {
                      let elFixDiv = document.createElement("div");
                      elFixDiv.innerHTML = elP.innerText;
                      elP.classList.forEach((cls) => {
                        elFixDiv.classList.add(cls);
                      });
                      elP.getAttributeNames().forEach((attr) => {
                        elFixDiv.setAttribute(attr, elP.getAttribute(attr));
                      });
                      elP.parentNode.insertBefore(elFixDiv, elP);
                      elP.parentNode.removeChild(elP);
                    }
                  });
                  html = elFix.innerHTML;
                  el.value = html;
                  self
                    .getManager()
                    .dataSet(component.$wire, modelKey, el.value);
                });
              },
              file_picker_callback: function (callback, value, meta) {
                window.showFileManager(function (content) {
                  callback(content[0].url);
                });
              },
            });
            // setTimeout(() => {
              
            //   el.$wire_tinymce.setContent(el.value);
            // }, 200);
          };
          if (window.tinymce) {
            tinymceInit();
          } else {
            window.addScriptToWindow(
              self
                .getManager()
                .getUrlPublic("platform/modules/sokeio/tinymce/tinymce.min.js"),
              function () {
                setTimeout(() => {
                  tinymceInit();
                }, 10);
              }
            );
          }
        }
      );
    }
  }
}
