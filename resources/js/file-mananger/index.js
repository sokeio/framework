import manager from "./component/manager";

window.showFileManager = function (callback, type = "file") {
  window.sokeioUI.run(manager).showFileManager(callback, type);
};
