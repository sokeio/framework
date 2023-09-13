import { ByteEvent } from "./event";
import { BytePlugin } from "./plugin";

export class ByteManager extends ByteEvent {
  plugins: any = {};
  constructor() {
    super();
  }
  registerPlugin(plugin: any) {
    const self = this;
    let _plugin: BytePlugin = new plugin();
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
    self.dispatch("byte::init", {
      manager: self,
    });
    self.dispatchDocument("byte::init", {
      manager: self,
    });
  }
  public register() {
    const self = this;
    self.dispatch("byte::register", {
      manager: self,
    });
    self.dispatchDocument("byte::register", {
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
    self.dispatch("byte::booting", {
      manager: self,
    });
    self.dispatchDocument("byte::booting", {
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
    self.dispatch("byte::booted", {
      manager: self,
    });
    self.dispatchDocument("byte::booted", {
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
    self.dispatch("byte::dispose", {
      manager: self,
    });
    self.dispatchDocument("byte::dispose", {
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
    if (key === "") return object;

    return key.split(".").reduce((carry, i) => {
      if (carry === undefined) return undefined;

      return carry[i];
    }, object);
  }
}
