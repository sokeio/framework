import { BaseJS } from "./base";
import { FeatureManager } from "./feature";
import {} from "./utils";

export class Component extends BaseJS {
  appComponentId = 0;
  appInstance = null;
  appEl = null;
  appParent = null;
  appChildren = [];
  appReady = [];
  appDestroy = [];
  appProps = null;
  appFeature = null;
  setProps(props) {
    this.appProps = props;
    return this;
  }
  setParent($parent) {
    this.appParent = $parent;
    return this;
  }
  setChild($child) {
    if (!this.appChildren) {
      this.appChildren = [];
    }
    if (!Array.isArray($child)) {
      $child = [$child];
    }
    this.appChildren = [...this.appChildren, ...$child];
    return this;
  }
  clearChild() {
    this.appChildren.forEach((child) => {
      child.destroy();
    });
    this.appChildren = [];
  }
  onReady($callback) {
    this.appReady.push($callback);
  }
  onDestroy($callback) {
    this.appDestroy.push($callback);
  }
  query(selectorOrEl) {
    if (typeof selectorOrEl === "string") {
      return this.appEl.querySelector(selectorOrEl);
    }
    if (Array.isArray(selectorOrEl)) {
      return selectorOrEl[0];
    }
    return selectorOrEl;
  }
  queryAll(selectorOrEl) {
    if (typeof selectorOrEl === "string") {
      return this.appEl.querySelectorAll(selectorOrEl);
    }
    if (Array.isArray(selectorOrEl)) {
      return selectorOrEl;
    }
    return [selectorOrEl];
  }
  onAll(selector, event, callback) {
    let els = this.queryAll(selector);
    els.forEach((el) => {
      if (typeof event === "string") event = [event];
      event.forEach((ev) => {
        el.addEventListener(ev, callback);
      });
    });
    this.onDestroy(() => {
      els.forEach((el) => {
        if (typeof event === "string") event = [event];
        event.forEach((ev) => {
          el.removeEventListener(ev, callback);
        });
      });
    });
  }
  on(selector, event, callback) {
    this.onReady(() => {
      let el = this.query(selector);
      if (typeof event === "string") event = [event];
      event.forEach((ev) => {
        el.addEventListener(ev, callback);
      });
      this.onDestroy(() => {
        event.forEach((ev) => {
          el.removeEventListener(ev, callback);
        });
      });
    });
  }
  setText(selector, text) {
    let el = this.query(selector);
    el.innerText = text;
  }
  setHtml(selector, html) {
    let el = this.query(selector);
    el.innerHTML = html;
  }
  init() {
    ("init");
  }
  beforeInit() {
    console.log("init");
  }
  afterInit() {
    console.log("init");
  }
  beforeRender() {
    console.log("init");
  }
  render() {
    console.log("init");
  }
  afterRender() {
    console.log("init");
  }
  destroy() {
    console.log("init");
  }
  getId() {
    if (!this.appComponentId) {
      this.appComponentId = this.appInstance.nextId();
    }
    return this.appComponentId;
  }
  doInit() {
    this.beforeInit();
    this.init();
    this.afterInit();
  }
  doRender() {
    this.beforeRender();
    this.appEl = this.appInstance.convertHtmlToElement(this.render(), this);
    this.appEl.setAttribute("data-component-id", this.appComponentId);
    this.afterRender();
  }
  doReady() {
    let self = this;
    this.appReady.forEach((callback) => {
      callback.bind(self)();
    });
  }
  doFeature() {
    if (!this.appFeature) {
      this.appFeature = new FeatureManager(this);
    }
    this.appFeature.runFeatures();
  }
  runComponent() {
    this.doInit();
    this.doRender();
    this.doFeature();
    this.doReady();
  }
}
