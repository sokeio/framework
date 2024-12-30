import mediaStorage from "./index";
window.showMediaManager = function (callback, type = "file", multiple = true) {
  window.showModal("Media Storage Manager", {
    type: type,
    fnCallback: callback,
    template: mediaStorage,
    modalSize: "xxl",
    skipOverlayClose: true,
    multiple: multiple,
  });
};
