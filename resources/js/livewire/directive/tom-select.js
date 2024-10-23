export default {
  checkFirst: () => window["TomSelect"] !== undefined,
  local: {
    js: ["/platform/modules/sokeio/tom-select/tom-select.min.js"],
    css: ["/platform/modules/sokeio/tom-select/tom-select.css"],
  },
  cdn: {
    js: [
      "https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js",
    ],
    css: ["https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css"],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    if (el.$sokeio_tomselect) return;
    let remoteAction = el.getAttribute("wire:tom-select.remote-action");
    let optionBase64 = el.getAttribute("wire:tom-select.base64");
    if (optionBase64) {
      optionBase64 = JSON.parse(window.atob(optionBase64)) ?? {};
      optionBase64 = {
        valueField: "id",
        labelField: "name",
        searchField: "name",
        ...optionBase64,
        ...options,
      };
    } else {
      optionBase64 = {
        valueField: "id",
        labelField: "name",
        searchField: "name",
        ...options,
      };
    }
    if (remoteAction) {
      optionBase64 = {
        ...optionBase64,
        load: function (query, callback) {
          component.$wire.callActionUI(remoteAction, query).then(function (rs) {
            callback(rs);
          });
        },
      };
    }
    el.$sokeio_tomselect = new TomSelect(el, optionBase64);
  },
};
