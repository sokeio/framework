import SokeioUI from "../../sokeio/src/main";

import "./message";
import "./file-mananger";
import "./modal/index";
import "./livewire";
import "./alpine";
import { addScriptToWindow, addStyleToWindow } from "./utils";

window.addStyleToWindow = addStyleToWindow;
window.addScriptToWindow = addScriptToWindow;

window.sokeioUI = SokeioUI;
