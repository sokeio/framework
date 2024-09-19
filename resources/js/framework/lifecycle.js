import {
  Component,
  registerComponent,
  doBoot as componentDoBoot,
  doRender as componentDoRender,
  doDestroy as componentDoDestroy,
  doReady as componentDoReady,
} from "./common/Component";

let isReady = false;
let isRegister = false;

export function onRegister(callback) {
  document.addEventListener("sokeio::register", callback);
}
export function onBoot(callback) {
  document.addEventListener("sokeio::boot", callback);
}
export function onReady(callback) {
  if (isReady) {
    callback();
  } else {
    document.addEventListener("sokeio::ready", callback);
  }
}
export function onDestroy(callback) {
  document.addEventListener("sokeio::destroy", callback);
}

export function register() {
  document.dispatchEvent(
    new CustomEvent("sokeio::register", { detail: { registerComponent } })
  );
}
export function boot(component) {
  componentDoBoot(component);
  document.dispatchEvent(new CustomEvent("sokeio::boot"));
}
export function render(component) {
  componentDoRender(component);
  document.dispatchEvent(new CustomEvent("sokeio::render"));
}
export function ready(component) {
  componentDoReady(component);
  document.dispatchEvent(new CustomEvent("sokeio::ready"));
  isReady = true;
}
export function destroy(component) {
  componentDoDestroy(component);
  document.dispatchEvent(new CustomEvent("sokeio::destroy"));
}
export function run(template = {}, querySelectorOrEl = null) {
  if (!isRegister) {
    register();
    isRegister = true;
  }
  document.dispatchEvent(new CustomEvent("sokeio::run"));
  let appComponent = new Component(template, {});
  boot(appComponent);
  render(appComponent);
  ready(appComponent);
  if (!querySelectorOrEl) {
    querySelectorOrEl = document.body;
  }
  if (typeof querySelectorOrEl === "string") {
    querySelectorOrEl = document.querySelector(querySelectorOrEl);
  }
  if (querySelectorOrEl) {
    querySelectorOrEl.appendChild(appComponent.$el);
  }
  return appComponent;
}
