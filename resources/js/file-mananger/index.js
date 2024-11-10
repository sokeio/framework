import manager from "./component/manager";

window.showFileManager = function (callback, type = "file") {
  window.showModal("File Manager", {
    type: type,
    callback: callback,
    template: manager,
    modalSize: "xxl",
    skipOverlayClose: true,
  });
};
// setTimeout(() => {
//   window.showFileManager(function (file) {
//     console.log(file);
//   });
// }, 100);
