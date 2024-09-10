import Application from "./Application";
import Component from "./Component";

window.sokeio = {
  Application: Application,
  Component: Component,
};
window.sokeioApp = new Application();

export default window.sokeioApp;