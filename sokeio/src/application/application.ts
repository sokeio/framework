import { logDebug } from "./utils";
import { getWireIdFromElement } from "./common";
import { Component } from "./component";
import { boot, ready, register, render } from "./lifecycle";

export function application(template: any = {}, options: any = {}) {
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
  // let init = options.init === undefined ? true : options.init;
  document.dispatchEvent(new CustomEvent("sokeio::run"));
  let wireId = getWireIdFromElement(querySelectorOrEl);
  if (options?.props?.wireId) {
    wireId = options.props.wireId;
  }

  if (wireId) {
    options.props = {
      ...options.props,
      wireId,
      $wire: (window as any).Livewire.find(wireId),
    };
  }
  let appComponent: any = Component(templateCopy, options.props ?? {}, null);
  appComponent.$app = appComponent;
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