import { ByteEvent } from "./event";
import { SokeioPlugin } from "./plugin";

export class SokeioManager extends ByteEvent {
  plugins: any = {};
  constructor() {
    super();
  }
  registerPlugin(plugin: any) {
    const self = this;
    let _plugin: SokeioPlugin = new plugin();
    _plugin.manager(self);
    self.plugins[_plugin.getKey()] = _plugin;
  }
  find(key: string) {
    return this.plugins[key];
  }

  appendHtmlToBody(html: string) {
    const elHtml: any = this.htmlToElement(html);
    if (document.body) {
      document.body.appendChild(elHtml);
    }
    return elHtml;
  }
  htmlToElement(html: string) {
    var template = document.createElement("template");
    // html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.firstChild;
  }
  htmlToElements(html: string) {
    var template = document.createElement("template");
    template.innerHTML = html;
    return template.content.childNodes;
  }
  public init() {
    const self = this;
    self.dispatch("sokeio::init", {
      manager: self,
    });
    self.dispatchDocument("sokeio::init", {
      manager: self,
    });
  }
  public register() {
    const self = this;
    self.dispatch("sokeio::register", {
      manager: self,
    });
    self.dispatchDocument("sokeio::register", {
      manager: self,
    });
    Object.keys(self.plugins).forEach((name) => {
      try {
        if (self.plugins[name].register) {
          self.plugins[name].register();
        }
      } catch (ex) {
        console.log({ register: ex });
      }
    });
  }
  public booting() {
    const self = this;
    self.dispatch("sokeio::booting", {
      manager: self,
    });
    self.dispatchDocument("sokeio::booting", {
      manager: self,
    });
    Object.keys(self.plugins).forEach((name) => {
      try {
        if (self.plugins[name].booting) {
          self.plugins[name].booting();
        }
      } catch (ex) {
        console.log({ register: ex });
      }
    });
  }
  public booted() {
    const self = this;
    self.dispatch("sokeio::booted", {
      manager: self,
    });
    self.dispatchDocument("sokeio::booted", {
      manager: self,
    });
    Object.keys(self.plugins).forEach((name) => {
      try {
        if (self.plugins[name].booted) {
          self.plugins[name].booted();
        }
      } catch (ex) {
        console.log({ register: ex });
      }
    });
  }
  public dispose() {
    const self = this;
    self.dispatch("sokeio::dispose", {
      manager: self,
    });
    self.dispatchDocument("sokeio::dispose", {
      manager: self,
    });
    Object.keys(self.plugins).forEach((name) => {
      try {
        if (self.plugins[name].dispose) {
          self.plugins[name].dispose();
        }
      } catch (ex) {
        console.log({ dispose: ex });
      }
    });
    self.uninitEventDocument();
    self.plugins = {};
    self.events = {};
    self.eventDocuments = {};
  }
  public start() {
    this.init();
    this.register();
    this.booting();
    this.booted();
    this.initEventDocument();
  }
  restart() {
    this.dispose();
    this.start();
  }
  public dataSet(object: any, key: string, value: any) {
    if (!key || !object) {
      return;
    }
    let segments = key.split(".");

    if (segments.length === 1) {
      return (object[key] = value);
    }

    let firstSegment = segments.shift();
    let restOfSegments = segments.join(".");
    if (firstSegment) {
      if (object[firstSegment] === undefined) {
        object[firstSegment] = {};
      }
      this.dataSet(object[firstSegment], restOfSegments, value);
    }
  }
  public dataGet(object: any, key: string) {
    if (key === "" || !key) return object;

    return key.split(".").reduce((carry, i) => {
      if (carry === undefined) return undefined;

      return carry[i];
    }, object);
  }
}
