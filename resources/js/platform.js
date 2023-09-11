import { PlatformEvent } from "./event";
import axios from "axios";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import { dataSet, dataGet } from "./utils";
export class PlatformModule extends PlatformEvent {
  $config = {};
  $module = {};
  $eventDocuments = {};
  $loaded = false;
  $debug = false;
  $axios = undefined;
  dataSet = dataSet;
  dataGet = dataGet;

  getCsrfToken() {
    if (this.$config["csrf_token"]) return this.$config["csrf_token"];
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag && tokenTag.content) {
      return tokenTag.content;
    }

    return window.livewire_token;
  }

  appendHtmlToBody(html) {
    const elHtml = this.htmlToElement(html);
    if (document.body) {
      document.body.appendChild(elHtml);
    }
    return elHtml;
  }
  htmlToElement(html) {
    var template = document.createElement("template");
    // html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.firstChild;
  }
  htmlToElements(html) {
    var template = document.createElement("template");
    template.innerHTML = html;
    return template.content.childNodes;
  }
  doTrigger(el) {
    if (el && !el.__deleting) {
      this.dispatch("byte::trigger_before", el);
      this.dispatch("byte::trigger", el);
      this.dispatch("byte::trigger_after", el);
    }
  }
  addError(error, component = "byteplatform", meta = {}) {
    this.addMessage(error, "error", component, meta);
  }
  addInfo(message, component = "byteplatform", meta = {}) {
    this.addMessage(message, "info", component, meta);
  }
  addMessage(message, type, component = "byteplatform", meta = {}) {
    this.dispatch("byte::message", {
      message,
      type,
      component,
      meta,
    });
  }
  getUrlPublic($url) {
    return this.$config["url"] + "/" + $url;
  }
  getUrl($url) {
    return this.$config["byte_url"] + "/" + $url;
  }
  register(name, $_module) {
    const self = this;
    self.$module[name] = $_module;
    if (self.$loaded && self.$module[name]) {
      self.$module[name].manager = self;
      try {
        if (self.$module[name].init) {
          self.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
      try {
        if (self.$module[name].loading) {
          self.$module[name].loading();
        }
      } catch (ex) {
        console.log("loading", name, ex);
      }
    }
  }
  find($name) {
    return this.$module[$name];
  }
  init() {
    document.dispatchEvent(
      new window.Event("BytePlatformInit", {
        bubbles: true,
        cancelable: false,
      })
    );
    document.dispatchEvent(
      new window.Event("byte::register", {
        bubbles: true,
        cancelable: false,
      })
    );

    let csrfToken = this.getCsrfToken();
    this.$axios = axios.create({
      baseURL: this.$config["url"],
      timeout: 1000,
      headers: {
        "Content-Type": "application/json",
        Accept: "text/html, application/xhtml+xml",
        "x-byteplatform": true,
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
      },
    });
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      self.$module[name].manager = self;
      try {
        if (self.$module[name].init) {
          self.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
    });
    return this;
  }
  loading() {
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (self.$module[name].loading) {
            self.$module[name].loading();
          }
        } catch (ex) {
          console.log("loading", name, ex);
        }
      });
    });
    self.dispatch("byte::loaded", self);
    Object.keys(self.$eventDocuments).forEach((event) => {
      try {
        let events = self.$eventDocuments[event];
        Object.keys(events).forEach(function (selector) {
          let callback = events[selector];
          document.addEventListener(event, function (ev) {
            let targetCurrent = ev.target;
            if (targetCurrent.matches(selector)) {
              callback && callback(ev);
            } else if ((targetCurrent = ev.target.closest(selector))) {
              // ev.target = targetCurrent;
              callback && callback({ ...ev, target: targetCurrent });
            }
          });
        });
      } catch (ex) {}
    });
    self.$loaded = true;

    document.body.dispatchEvent(
      new window.Event("BytePlatformLoaded", {
        bubbles: true,
        cancelable: false,
      })
    );
  }
  uninit() {
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (self.$module[name].uninit) {
            self.$module[name].uninit();
          }
        } catch (ex) {
          console.log("uninit", name, ex);
        }
      });
    });
    Object.keys(self.$eventDocuments).forEach((event) => {
      try {
        let events = self.$eventDocuments[event];
        Object.keys(events).forEach(function (selector) {
          let callback = events[selector];
          document.removeEventListener(event, function (ev) {
            let targetCurrent = ev.target;
            if (targetCurrent.matches(selector)) {
              callback && callback(ev);
            } else if ((targetCurrent = ev.target.closest(selector))) {
              // ev.target = targetCurrent;
              callback && callback({ ...ev, target: targetCurrent });
            }
          });
        });
      } catch (ex) {}
    });
    this.$events = {};
    self.$eventDocuments = {};
    self.$loaded = true;
  }
  restart() {
    this.uninit();
    self.$loaded = false;
    this.start();
  }
  reloading() {
    this.uninit();
    this.init();
    this.loading();
    self.$loaded = true;
  }

  start() {
    const self = this;
  }
  onDocument(event, selector, callback) {
    const self = this;
    if (!self.$eventDocuments[event]) self.$eventDocuments[event] = {};
    self.$eventDocuments[event][selector] = callback;
  }
  showFileManager(callback, type = "file") {
    if (this.$config["byte_filemanager"]) {
      window.open(
        this.$config["byte_filemanager"] + "?type=" + (type || "file"),
        "FileManager",
        "width=900,height=600"
      );
      window.SetUrl = function (items) {
        callback && callback(items);
      };
    }
  }
  openModal($option, dataModal = undefined) {
    this.$module["BYTE_MODAL_MODULE"].openModal($option, dataModal);
  }
  openShortcodeSetting($shortcode, $attrs = [], $child, callback = undefined) {
    let ShortcodeEventCallBack =
      "ShortcodeEventCallBack" + new Date().getTime();
    window[ShortcodeEventCallBack] = callback;
    this.openModal(
      {
        $url: this.$config["byte_shortcode_setting"],
        $title: "Shortcode Setting",
      },
      {
        shortcode: $shortcode,
        attrs: $attrs,
        children: $child,
        callbackEvent: ShortcodeEventCallBack,
      }
    );
  }
}
export const modulePlatform = new PlatformModule();
