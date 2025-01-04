import { dataSet } from "../../utils";

export default {
  checkFirst: () => window.tinymce !== undefined,
  local: {
    js: ["/platform/module/sokeio/tinymce/tinymce.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },

  init: ({ el, directive, component, cleanup, options }) => {
    if (el.$sokeio_tinymce) return;

    cleanup(() => {
      if (el.$sokeio_tinymce && el.$sokeio_tinymce.remove) {
        el.$sokeio_tinymce.remove();
        el.$sokeio_tinymce = null;
      }
    });
    let modelKey =
      el.getAttribute("wire:tinymce-model") ?? el.getAttribute("wire:model");
    let config = {};
    if (el.hasAttribute("wire:tinymce-skip")) {
      config = {};
    }
    el.$sokeio_tinymce = window.tinymce.init({
      ...(config ?? {}),
      ...options,
      promotion: false,
      target: el,
      setup: function (editor) {
        editor.on("init", function () {
          editor.setContent(el.value);
          editor.undoManager.dispatchChange();
        });
        editor.on("input", function (e) {
          editor.fire("SaveContent");
          dataSet(component.$wire, modelKey, editor.getContent());
          editor.fire("AfterSaveContent");
        });
        editor.on("ExecCommand", (e) => {
          if (["mceFocus"].includes(e.command)) return;
          dataSet(component.$wire, modelKey, editor.getContent());
        });
      },
      file_picker_callback: function (callback, value, meta) {
        window.showMediaManager(
          function (content) {
            if (Array.isArray(content)) {
              content = content[0];
            }
            callback(content.public_url);
          },
          {
            type: "image",
            value: value,
          }
        );
      },
    });
  },
};
