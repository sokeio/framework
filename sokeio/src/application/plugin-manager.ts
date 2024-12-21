import { addScriptToWindow, addStyleToWindow } from "./../utils";

export class Plugin {
  css: any[] = [];
  js: any[] = [];
  public check() {
    return true;
  }
  public excute(_component: any, _event: any) {
    console.log("excute", _event, _component, this.css, this.js);
  }
}

export class PluginManager {
  constructor($plugins: any = []) {
    let plugins = $plugins;
    if (!Array.isArray(plugins)) {
      plugins = [plugins];
    }
    plugins.forEach((plugin: any) => {
      this.register(plugin);
    });
  }
  plugins: any[] = [];
  public register(plugin: any) {
    let newPlugin = new Plugin();
    if (plugin.css) {
      newPlugin.css = plugin.css;
    }
    if (plugin.js) {
      newPlugin.js = plugin.js;
    }
    if (plugin.excute) {
      newPlugin.excute = plugin.excute;
    }
    if (plugin.check) {
      newPlugin.check = plugin.check;
    }
    this.plugins.push(newPlugin);
  }
  private loadPlugin(_plugin: any) {
    if (_plugin.check()) {
      return;
    }
    addScriptToWindow(_plugin.js);
    addStyleToWindow(_plugin.css);
  }
  public load() {
    this.plugins.forEach((plugin) => {
      this.loadPlugin(plugin);
    });
  }
  public excute(_component: any, _event: any) {
    this.plugins.forEach((plugin) => {
      plugin.excute(_component, _event);
    });
  }
}
