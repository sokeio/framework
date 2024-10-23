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
    let dataSource = el.getAttribute("wire:tom-select.data-source");
    if (dataSource) {
      dataSource = JSON.parse(dataSource) ?? {};
    }
    if (optionBase64) {
      optionBase64 = JSON.parse(window.atob(optionBase64)) ?? {};
      optionBase64 = {
        ...optionBase64,
        ...options,
      };
    } else {
      optionBase64 = {
        ...options,
      };
    }
    if (remoteAction) {
      optionBase64 = {
        ...optionBase64,
        preload: true,
        load: function (query, callback) {
          component.$wire.callActionUI(remoteAction, query).then(function (rs) {
            callback(rs);
          });
        },
      };
    }
    if (dataSource) {
      optionBase64 = {
        ...optionBase64,
        options: dataSource,
      };
    }
    el.$sokeio_tomselect = new TomSelect(el, optionBase64);
  },
};