export default {
  checkFirst: () => window["TomSelect"] !== undefined,
  local: {
    js: ["/platform/module/sokeio/tom-select/tom-select.min.js"],
    css: ["/platform/module/sokeio/tom-select/tom-select.css"],
  },
  cdn: {
    js: [
      "https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js",
    ],
    css: ["https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css"],
  },
  init: ({ el, directive, component, cleanup, succeed, options }) => {
    let warpSelect = el;
    el = el.querySelector("select");
    let dataSource = warpSelect.getAttribute("wire:tom-select.dataSource");
    if (dataSource) {
      dataSource = JSON.parse(dataSource) ?? {};
    }
    let remoteAction = warpSelect.getAttribute("wire:tom-select.remote-action");
    let optionSelect = warpSelect.getAttribute("wire:tom-select.base64");
    if (optionSelect) {
      optionSelect = JSON.parse(window.atob(optionSelect)) ?? {};
      optionSelect = {
        ...optionSelect,
        ...options,
      };
    } else {
      optionSelect = {
        ...options,
      };
    }
    let queryText = "";
    if (remoteAction) {
      optionSelect = {
        ...optionSelect,
        load: function (query, callback) {
          queryText = query;
          component.$wire
            .callActionUI(remoteAction, queryText)
            .then(function (rs) {
              callback(rs);
            });
        },
      };
    }
    if (dataSource) {
      optionSelect = {
        ...optionSelect,
        options: dataSource,
      };
    }
    el.$sokeio_tomselect = new TomSelect(el, optionSelect);
    cleanup(() => {
      el.$sokeio_tomselect.destroy();
      el.$sokeio_tomselect = null;
    });
  },
};
