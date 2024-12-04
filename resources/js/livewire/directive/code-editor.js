import { dataGet, dataSet } from "../../utils";

export default {
  checkFirst: () => window.ace !== undefined,
  local: {
    js: [],
    css: [],
  },
  cdn: {
    js: ["https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.5/ace.min.js"],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    let modelKey =
      el.getAttribute("wire:code-editor-model") ??
      el.getAttribute("wire:model");
    el.$sokeio_code_editor = ace.edit(el, {
      ...options,
      value: dataGet(component.$wire, modelKey) ?? "",
      selectionStyle: "text",
    });
    let language = el.getAttribute("wire:code-editor.language") ?? "javascript";
    el.$sokeio_code_editor.session.setMode("ace/mode/" + language);
    el.$sokeio_code_editor.session.on("change", function (delta) {
      setTimeout(function () {
        dataSet(component.$wire, modelKey, el.$sokeio_code_editor.getValue());
      }, 10);
    });
    cleanup(() => {
      el.$sokeio_code_editor.destroy();
      el.$sokeio_code_editor.container.remove();
    });
  },
};
