import { getWireIdFromElement, logDebug } from "./../utils";
import { PluginManager } from "./plugin-manager";
import featurePlugin from "../plugins/feature";
import { Component } from "../component";

export function application(template: any = {}, options: any = {}) {
  let querySelectorOrEl = options.selector;
  if (!querySelectorOrEl) {
    querySelectorOrEl = document.body;
  }
  if (typeof querySelectorOrEl === "string") {
    querySelectorOrEl = document.querySelector(querySelectorOrEl);
  }
  let templateApp = {
    ...template,
    sokeAppSelector: querySelectorOrEl,
    state: JSON.parse(JSON.stringify(template.state ?? {})),
  };

  logDebug("templateApp", templateApp);
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
  if (!options?.plugins) {
    options.plugins = [];
  }
  let $plugin = new PluginManager([...options.plugins, featurePlugin]);
  document.dispatchEvent(
    new CustomEvent("sokeio::plugin::load", { detail: $plugin })
  );
  $plugin.load();
  options.props = {
    ...options.props,
    $plugin,
  };
  let appComponent: any = Component(templateApp, options.props ?? {}, null);
  appComponent.$app = options?.$app ?? appComponent;
  if (options.components) {
    let components = options.components;
    if (typeof components === "string") {
      components = JSON.parse(options.components);
    }
    appComponent.$app.$components = appComponent.$app.$components ?? {};
    if (components) {
      appComponent.$app.$components = {
        ...appComponent.$app.$components,
        ...components,
      };
      console.log(components);
    }
  }
  appComponent.doRegister();
  appComponent.doBoot();
  appComponent.doRender();
  appComponent.doReady();
  if (!appComponent.isHide) {
    appComponent.show();
  }
  return appComponent;
}
