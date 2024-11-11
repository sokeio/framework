import {
  Component,
  registerComponent,
  doBoot as componentDoBoot,
  doRender as componentDoRender,
  doDestroy as componentDoDestroy,
  doReady as componentDoReady,
  $request as componentRequest,
} from "./common/Component";
import { logDebug } from "./common/Uitls";

export const $request = componentRequest;
export function onRegister(callback) {
  document.addEventListener("sokeio::register", callback);
}
export function onBoot(callback) {
  document.addEventListener("sokeio::boot", callback);
}
export function onReady(callback) {
  document.addEventListener("sokeio::ready", callback);
}
export function onDestroy(callback) {
  document.addEventListener("sokeio::destroy", callback);
}

export function register(component) {
  document.dispatchEvent(
    new CustomEvent("sokeio::register", {
      detail: { registerComponent, component },
    })
  );
}
export function boot(component) {
  componentDoBoot(component);
  document.dispatchEvent(
    new CustomEvent("sokeio::boot", { detail: { component } })
  );
}
export function render(component) {
  componentDoRender(component);
  document.dispatchEvent(
    new CustomEvent("sokeio::render", { detail: { component } })
  );
}
export function ready(component) {
  componentDoReady(component);
  document.dispatchEvent(
    new CustomEvent("sokeio::ready", { detail: { component } })
  );
}
export function destroy(component) {
  componentDoDestroy(component);
  document.dispatchEvent(
    new CustomEvent("sokeio::destroy", { detail: { component } })
  );
}
export function run(template = {}, options = {}) {
  let querySelectorOrEl = options.selector;
  if (!querySelectorOrEl) {
    querySelectorOrEl = document.body;
  }
  if (typeof querySelectorOrEl === "string") {
    querySelectorOrEl = document.querySelector(querySelectorOrEl);
  }
  let templateCopy = {
    ...template,
    sokeAppSelector: querySelectorOrEl,
    state: JSON.parse(JSON.stringify(template.state ?? {})),
  };
  if (options.components) {
    if (typeof options.components === "string") {
      options.components = JSON.parse(options.components);
    }
    templateCopy.components = templateCopy.components ?? {};
    templateCopy.components = {
      ...templateCopy.components,
      ...options.components,
    };
  }
  logDebug("templateCopy", templateCopy);
  let init = options.init === undefined ? true : options.init;
  document.dispatchEvent(new CustomEvent("sokeio::run"));
  if (options?.props?.wireId) {
    options.props = {
      ...options.props,
      wireId: options.props.wireId,
      $wire: window.Livewire.find(options.props.wireId),
    };
  }
  let appComponent = new Component(templateCopy, options.props ?? {});
  appComponent.$root = appComponent;
  register(appComponent);

  logDebug("run", appComponent);
  boot(appComponent);
  render(appComponent);
  ready(appComponent);
  if (!appComponent.hide) {
    appComponent.show();
  }
  return appComponent;
}
