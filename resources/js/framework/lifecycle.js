import { Component, registerComponent } from "./common/Component";

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
  component && component.doBoot();
  document.dispatchEvent(new CustomEvent("sokeio::boot"));
}
export function render(component) {
  component && component.doRender();
  document.dispatchEvent(new CustomEvent("sokeio::render"));
}
export function ready(component) {
  component && component.doReady();
  document.dispatchEvent(new CustomEvent("sokeio::ready"));
  isReady = true;
}
export function destroy(component) {
  component && component.doDestroy();
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
