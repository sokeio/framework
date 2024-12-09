import SokeioUI from "../../sokeio/src/main";

import "./message";
import "./file-mananger";
import "./modal/index";
import "./livewire";
import "./alpine";
import { addScriptToWindow, addStyleToWindow } from "./utils";
import icon from "./icon";

window.addStyleToWindow = addStyleToWindow;
window.addScriptToWindow = addScriptToWindow;

window.sokeioUI = SokeioUI;
window.showModal("File Manager", {
  fnCallback: function (file) {
    console.log(file);
  },
  template: icon,
  modalSize: "xxl",
  skipOverlayClose: true,
});
