export class LiveWireTinymceModule {
  manager = undefined;
  shortCode = false;
  addShortCode() {
    return;
  }
  init() {}
  loading() {
    const self = this;
    if (window.Livewire) {
      window.Livewire.directive(
        "tinymce",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0 || el.livewire____tinymce) {
            return;
          }
          let options = {};
          if (el.hasAttribute("wire:tinymce")) {
            options = new Function(
              `return ${el.getAttribute("wire:tinymce")};`
            )();
          }
          let modelKey =
            el.getAttribute("wire:tinymce-model") ??
            el.getAttribute("wire:model");
          const tinymceInit = () => {
            self.addShortCode();
            if (el.livewire____tinymce) return;
            el.livewire____tinymce = window.tinymce.init({
              ...(self.manager.$config["tinyecm_option"] ?? {}),
              ...options,
              promotion: false,
              target: el,
              setup: function (editor) {
                editor.on("init change", function () {
                  editor.save();
                });
                editor.on("change", function (e) {
                  if (window.removeHighlightShortcodes) {
                    el.value = window.removeHighlightShortcodes(
                      editor.getContent()
                    );
                  } else {
                    el.value = editor.getContent();
                  }

                  self.manager.dataSet(component.$wire, modelKey, el.value);
                });
              },
              file_picker_callback: function (callback, value, meta) {
                if (!self.manager.$config["byteplatform_filemanager"]) return;
                var x =
                  window.innerWidth ||
                  document.documentElement.clientWidth ||
                  document.getElementsByTagName("body")[0].clientWidth;
                var y =
                  window.innerHeight ||
                  document.documentElement.clientHeight ||
                  document.getElementsByTagName("body")[0].clientHeight;

                var cmsURL =
                  self.manager.$config["byteplatform_filemanager"] +
                  "?editor=" +
                  meta.fieldname;
                if (meta.filetype == "image") {
                  cmsURL = cmsURL + "&type=Images";
                } else {
                  cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                  url: cmsURL,
                  title: "Filemanager",
                  width: x * 0.8,
                  height: y * 0.8,
                  resizable: "yes",
                  close_previous: "no",
                  onMessage: (api, message) => {
                    callback(message.content);
                  },
                });
              },
            });
          };
          if (window.tinymce) {
            tinymceInit();
          } else {
            window.addScriptToWindow(
              self.manager.getUrlPublic(
                "byteplatform/modules/byteplatform/tinymce/tinymce.min.js"
              ),
              function () {
                setTimeout(() => {
                  tinymceInit();
                }, 50);
              }
            );
          }
        }
      );
    }
  }
  unint() {}
}
