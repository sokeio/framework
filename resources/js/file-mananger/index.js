import manager from "./file-manager";

window.showFileManager = function (callback, type = "file", multiple = false) {
  window.showModal("File Manager", {
    type: type,
    callback: callback,
    template: manager,
    modalSize: "xxl",
    skipOverlayClose: true,
    multiple: multiple,
  });
};
// setTimeout(() => {
//   window.showFileManager(function (file) {
//     console.log(file);
//   });
// }, 100);
