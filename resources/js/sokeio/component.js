import { BaseJS } from "./base";
import { FeatureManager } from "./feature";

export class Component extends BaseJS {
  $main = null;
  $id = 0;
  $el = null;
  $children = [];
  $props = null;
  __eInit = [];
  __eReady = [];
  __eMount = [];
  __eDestroy = [];
  __eFeature = null;
  bodyOverflowHide($class = "overflow-hide") {
    document.body.classList.add($class);
    this.onDestroy(() => {
      document.body.classList.remove($class);
    });
  }
  setProps($props) {
    this.$props = $props;
    return this;
  }
  setChild($child) {
    if (!this.$children) {
      this.$children = [];
    }
    if (!Array.isArray($child)) {
      $child = [$child];
    }
    this.$children = [...this.$children, ...$child];
    return this;
  }

  watch(property, callback, destroy = undefined) {
    if (destroy === undefined) {
      super.watch(property, callback, ($callback) => {
        this.onDestroy(() => {
          $callback();
        });
      });
    } else {
      super.watch(property, callback, destroy);
    }
    return this;
  }
  clearChild() {
    this.$children.forEach((child) => {
      child.destroy();
    });
    this.$children = [];
    return this;
  }
  onInit($callback) {
    this.__eInit.push($callback);
    return this;
  }
  onReady($callback) {
    this.__eReady.push($callback);
    return this;
  }
  onMount($callback) {
    this.__eMount.push($callback);
    return this;
  }
  onDestroy($callback) {
    this.__eDestroy.push($callback);
    return this;
  }
  query(selectorOrEl, $callback = null) {
    if ($callback) {
      let el = this.query(selectorOrEl);
      $callback.bind(this)(el);
      return el;
    }
    if (typeof selectorOrEl === "string") {
      return this.$el.querySelector(selectorOrEl);
    }
    if (Array.isArray(selectorOrEl)) {
      return selectorOrEl[0];
    }
    return selectorOrEl;
  }
  queryAll(selectorOrEl, $callback = null) {
    if ($callback) {
      let els = this.queryAll(selectorOrEl);
      els.forEach(($el) => {
        $callback.bind(this)($el);
      });
      return els;
    }
    if (typeof selectorOrEl === "string") {
      return this.$el.querySelectorAll(selectorOrEl);
    }
    if (Array.isArray(selectorOrEl)) {
      return selectorOrEl;
    }
    return [selectorOrEl];
  }
  touchClass(className, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    if (el.classList.contains(className)) {
      el.classList.remove(className);
    } else {
      el.classList.add(className);
    }
    return this;
  }
  addClass(className, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.classList.add(className);
    return this;
  }
  removeClass(className, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.classList.remove(className);
    return this;
  }
  setClass(className, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.className = className;
    return this;
  }
  getClasses(selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.classList;
  }
  hasClass(className, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.classList.contains(className);
  }
  getAttribute(attribute, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.getAttribute(attribute);
  }
  setAttribute(attribute, value, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.setAttribute(attribute, value);
    return this;
  }
  removeAttribute(attribute, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.removeAttribute(attribute);
    return this;
  }
  getValue(selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.value;
  }
  setValue(value, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.value = value;
    return this;
  }
  getText(selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.innerText;
  }
  setText(text, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.innerText = text;
  }
  setHtml(html, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    el.innerHTML = html;
  }
  getHtml(selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    return el.innerHTML;
  }
  setStyle(style, selector = null) {
    let el = selector ? this.query(selector) : this.$el;
    Object.assign(el.style, style);
  }

  onAll(event, callback, selector = null) {
    this.onReady(() => {
      let els = selector ? this.queryAll(selector) : [this.$el];
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
    });
    return this;
  }
  onResize(callback) {
    this.onReady(() => {
      window.addEventListener("resize", callback);
      this.onDestroy(() => {
        window.removeEventListener("resize", callback);
      });
    });
    return this;
  }
  on(event, callback, selector = null) {
    this.onReady(() => {
      let el = selector ? this.query(selector) : this.$el;
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
    return this;
  }

  beforeInit() {
    // do before init
  }
  init() {
    // do init
  }
  afterInit() {
    // do after init
  }
  beforeRender() {
    // do before render
  }
  render() {
    // do render
    // return `
    // <div class="component-wrapper">
    // </div>
    // `;
  }
  afterRender() {
    // do after render
  }
  destroy() {
    this.__eDestroy.forEach((callback) => {
      callback.bind(this)();
    });
    this.clearChild();
    this.$el?.remove();

    this.clearBase();
    this.$el = null;
  }
  getId() {
    if (!this.$id) {
      this.$id = this.$main.nextId();
    }
    return this.$id;
  }
  doInit() {
    this.beforeInit();
    this.init();
    let self = this;
    this.__eInit.forEach((callback) => {
      callback.bind(self)();
    });
    this.afterInit();
  }
  doRender() {
    this.beforeRender();
    let temp = this.$el;
    this.$el = this.$main.convertHtmlToElement(this.render(), this);
    this.$el.setAttribute("data-sokeio-id", this.getId());
    if (temp) {
      temp.parentNode.insertBefore(this.$el, temp);
      temp.remove();
    }
    this.afterRender();
  }
  doReady() {
    let self = this;
    this.__eReady.forEach((callback) => {
      callback.bind(self)();
    });
  }
  doMount() {
    let self = this;
    this.__eMount.forEach((callback) => {
      callback.bind(self)();
    });
  }
  doFeature() {
    if (!this.__eFeature) {
      this.__eFeature = new FeatureManager(this);
    }
    this.__eFeature.runFeatures();
  }
  doClose($callback = undefined) {
    this.destroy();
    if ($callback) {
      $callback();
    }
  }
  getArrayFuncs() {
    return [super.getArrayFuncs()];
  }

  runRender() {
    this.clearChild();
    this.doRender();
    this.doFeature();
    this.doReady();
  }
  runComponent() {
    this.doInit();
    this.runRender();
    this.doMount();
  }
}
