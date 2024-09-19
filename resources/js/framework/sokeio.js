import demo from "./components/demo";
import demo2 from "./components/demo2";
import * as lifecycle from "./lifecycle";

document.addEventListener(
  "sokeio::register",
  ({ detail: { registerComponent } }) => {
    registerComponent("demo::test", demo);
    registerComponent("sokeio::demo2", demo2);
  }
);

window.sokeio = {
  Application: lifecycle,
};
