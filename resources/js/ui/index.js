import { UI } from "./ui";
import { ButtonUI } from "./button";
import { InputUI } from "./input";
import { MakeObject } from "./object";
if (typeof window !== "undefined" && window.SokeioUI == undefined) {
  window.SokeioUI = {
    UI,
    ButtonUI,
    InputUI,
  };
}
export { UI, ButtonUI, InputUI, MakeObject };
