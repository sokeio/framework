import axios from "axios";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import _ from "lodash";
window._ = _;
import "lazysizes";
// import a plugin
import "lazysizes/plugins/parent-fit/ls.parent-fit";
import { SokeioManager } from "./core/manager";
import { FileManager } from "./file-mananger/index";
export class Sokeio extends SokeioManager {
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
    return this.$config["sokeio_url"] + "/" + $url;
  }
  getAxios() {
    return this.$axios;
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
    this.dispatch("sokeio::loaded", { app: this });
  }
  showFileManager(callback, type = "file") {
    FileManager.run(null, { type }, function () {
      this.onCallback((data) => {
        callback && callback(data);
      });
    });
  }
  openModal($option, dataModal = undefined) {
    return this.find("SOKEIO_MODAL_MODULE").openModal($option, dataModal);
  }
  openModalSetting(
    $key,
    $editorContainer,
    $data = {},
    callback = undefined,
    callbackClosed = undefined
  ) {
    let EventCallBack = $key + "EventCallBack" + new Date().getTime();
    window[EventCallBack] = callback;
    let parentEl = $editorContainer.closest("[wire\\:id]");
    let refComponent = parentEl?.getAttribute("wire:id");
    let $config = this.$config[$key] ?? [];
    this.openModal(
      {
        $url: $config["url"],
        $title: $config["title"] ?? "Setting",
        $size: $config["size"] ?? "modal-fullscreen-md-down modal-xl",
        $callbackClosed: callbackClosed,
      },
      {
        refComponent,
        soIsAdmin: Livewire.find(refComponent)?.soIsAdmin,
        ___setting_data: $data,
        ___setting_callback_event: EventCallBack,
      }
    );
  }
  openShortcodeSetting(
    $editorContainer,
    $shortcode,
    $attrs,
    $child,
    callback = undefined,
    callbackClosed = undefined
  ) {
    this.openModalSetting(
      "sokeio_shortcode_setting",
      $editorContainer,
      {
        shortcode: $shortcode,
        attrs: $attrs,
        children: $child,
      },
      callback,
      callbackClosed
    );
  }
  openIconSetting(
    $editorContainer,
    $icon,
    callback = undefined,
    callbackClosed = undefined
  ) {
    this.openModalSetting(
      "sokeio_icon_setting",
      $editorContainer,
      {
        icon: $icon,
      },
      callback,
      callbackClosed
    );
  }
}
