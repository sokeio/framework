import SokeioUI from "../../sokeio/src/main";

import "./message";
import "./modal/index";
import "./livewire";
import "./alpine";
import { addScriptToWindow, addStyleToWindow } from "./utils";
import "./media-storage/media-storage";
import icon from "./icon";

window.addStyleToWindow = addStyleToWindow;
window.addScriptToWindow = addScriptToWindow;

window.sokeioUI = SokeioUI;
