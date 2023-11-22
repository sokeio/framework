import { ByteManager } from "./core/manager";
import axios from "axios";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import _ from "lodash";
window._ = _;
export class Sokeio extends ByteManager {
  $config = {};
  $loaded = false;
  $debug = false;
  $axios = undefined;
  constructor() {
    super();
  }
  getCsrfToken() {
    if (this.$config["csrf_token"]) return this.$config["csrf_token"];
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag && tokenTag.content) {
      return tokenTag.content;
    }

    return window.livewire_token;
  }
  doTrigger(el) {
    if (el && !el.__deleting) {
      this.dispatch("sokeio::trigger_before", el);
      this.dispatch("sokeio::trigger", el);
      this.dispatch("sokeio::trigger_after", el);
    }
  }
  addError(error, component = "sokeio", meta = {}) {
    this.addMessage(error, "error", component, meta);
  }
  addInfo(message, component = "sokeio", meta = {}) {
    this.addMessage(message, "info", component, meta);
  }
  addMessage(message, type, component = "sokeio", meta = {}) {
    this.dispatch("sokeio::message", {
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
  init() {
    super.init();
    let csrfToken = this.getCsrfToken();
    this.$axios = axios.create({
      baseURL: this.$config["url"],
      timeout: 1000,
      headers: {
        "Content-Type": "application/json",
        Accept: "text/html, application/xhtml+xml",
        "x-sokeio": true,
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
      },
    });
  }
  start() {
    super.start();
    this.dispatch("sokeio::loaded", document);
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
    return this.find("BYTE_MODAL_MODULE").openModal($option, dataModal);
  }
  openShortcodeSetting(
    $editorContainer,
    $shortcode,
    $attrs = [],
    $child,
    callback = undefined,
    callbackClosed = undefined
  ) {
    let ShortcodeEventCallBack =
      "ShortcodeEventCallBack" + new Date().getTime();
    window[ShortcodeEventCallBack] = callback;

    let parentEl = $editorContainer.closest("[wire\\:id]");
    let refComponent = parentEl?.getAttribute("wire:id");
    this.openModal(
      {
        $url: this.$config["byte_shortcode_setting"],
        $title: "Shortcode Setting",
        $callbackClosed: callbackClosed,
        $size: "modal-fullscreen-md-down modal-xl",
      },
      {
        refComponent,
        ___theme___admin: Livewire.find(refComponent)?.___theme___admin,
        shortcode: $shortcode,
        attrs: $attrs,
        children: $child,
        callbackEvent: ShortcodeEventCallBack,
      }
    );
  }
}
