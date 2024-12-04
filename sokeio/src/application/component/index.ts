import { getComponents } from "../common";
import {
  convertHtmlToElement,
  getMethods,
  logDebug,
  tap,
  getComponentsFromText,
  getModalOverlay,
  tagSplit,
} from "../utils";
import { DataValue } from "./datavalue";
import { Hook } from "./hook";
// import feature from "../feature/_index";

import request from "./../request";
let number = new Date().getTime();
const componentDefine = {
  overlay: false,
  focusInput: false,
  state: {},
  register: () => {},
  boot: () => {},
  ready: () => {},
  render: () => {},
  destroy: () => {},
};
const componentMixin: any = {
  $id: 0,
  $el: null,
  $children: [],
  $request: request,
  __data__: null,
  __props__: null,
  __hooks__: null,
  $overlayEl: null,
  getId() {
    return this.$id;
  },
  onRegister: function (callback: any) {
    this.__hooks__.on("register", callback);
    return this;
  },
  onBoot: function (callback: any) {
    this.__hooks__.on("boot", callback);
    return this;
  },
  onRender: function (callback: any) {
    this.__hooks__.on("render", callback);
    return this;
  },
  onReady: function (callback: any) {
    this.__hooks__.on("ready", callback);
    return this;
  },
  onDestroy: function (callback: any) {
    this.__hooks__.on("destroy", callback);
    return this;
  },
  __tapChildren: function (callback: any) {
    if (this.$children && callback) {
      this.$children.forEach((item: any) => {
        callback.bind(this)(item);
      });
    }
    return this;
  },
  __lifecycle(name: any, data: any) {
    logDebug(`component:${name}:begin`, this, data);
    if (
      !name ||
      !["register", "boot", "ready", "render", "destroy"].includes(name)
    )
      return;
    this.$app.$plugin.excute(this, name);
    if (this[name]) {
      this[name].bind(this)();
    }
    this.dispatch(name, data);
    logDebug(`component:${name}:end`, this, data);
  },
  getComponent: function (_name: string, _props: any) {
    let components = getComponents();
    let template = undefined;
    if (this.components[_name]) {
      template = this.components[_name];
    }
    if (!template && this.$parent?.components?.[_name]) {
      template = this.$parent?.components?.[_name];
    }
    if (!template && this.$parent?.$app?.components?.[_name]) {
      template = this.$parent?.$app?.components?.[_name];
    }
    if (!template && components[_name]) {
      template = components[_name];
    }
    if (template) {
      return Component(template, _props, this);
    }
    console.error("Component not found: " + _name);
    return null;
  },
  processChildren() {
    let html = this.$el.innerHTML;

    let components = getComponentsFromText(html);
    let tempComponents: any = components.map((item) => {
      html = html.split(item.component).join(tagSplit);
      let childComponent = this.getComponent(item.tag, item.attrs);
      return childComponent;
    });
    if (tempComponents.length) {
      let templHtml = "";
      html.split(tagSplit).forEach((item: any, index: any) => {
        templHtml += item;
        if (tempComponents[index]) {
          templHtml +=
            '<span id="sokeio-component-' +
            tempComponents[index].getId() +
            '">' +
            tempComponents[index].getId() +
            "</span>";
        }
      });
      html = templHtml;
      this.$children = tempComponents;
    }

    this.$el.innerHTML = html;

    return this;
  },
  dispatch: function (_event: any, _data: any) {
    this.__hooks__.fire(_event, _data, this);
    document.dispatchEvent(
      new CustomEvent(`sokeio::${_event}`, {
        detail: { component: this, data: _data },
      })
    );
  },
  watch: function (_property: any, _callback: any) {
    if (this.__data__.check(_property)) {
      this.__data__.watch(_property, _callback.bind(this));
    }
    if (this.__props__.check(_property)) {
      this.__props__.watch(_property, _callback.bind(this));
    }
    return this;
  },
  doInit: function () {
    this.__data__ = new DataValue(this.$initState, this);
    this.__props__ = new DataValue(this.$initProps, this, true);
    this.__hooks__ = new Hook(this);
    this.$id = (number++).toString();
  },
  doRegister: function () {
    this.__lifecycle("register");
  },
  doBoot: function () {
    this.__lifecycle("boot");
    let html = this.render ? this.render() : "<div></div>";
    html = html.trim();
    if (this.$el) {
      this.$el.innerHTML = html;
    } else {
      this.$el = convertHtmlToElement(html);
    }
    this.processChildren();
    this.__tapChildren((item: any) => {
      item.doRegister();
      item.doBoot();
    });
  },
  doReady: function () {
    // feature(this);
    this.__lifecycle("ready");
    this.__tapChildren((item: any) => {
      item.doReady();
    });
  },
  doRender: function () {
    this.__lifecycle("render");
    this.__tapChildren((item: any) => {
      item.doRender();
      let elTemp = this.$el.querySelector("#sokeio-component-" + item.getId());
      elTemp.parentNode.insertBefore(item.$el, elTemp);
      elTemp.remove();
    });
    if (this.$el) {
      this.$el.setAttribute("data-sokeio-id", this.getId());
      this.$el.__sokeio = this;
    }
  },
  doDestroy: function () {
    this.__lifecycle("destroy");
    this.__tapChildren((item: any) => {
      item.doDestroy();
    });
    this.$el?.remove();
    this.__hooks__.destroy();
    this.$children = [];
    this.$el = null;
    this.state = {};
  },
  closeApp: function () {
    if (this.$app) {
      this.$app.doDestroy();
    }
  },
  refresh: function () {
    let elParent = this.$el.parentNode;
    let elNext = this.$el.nextSibling;
    this.$el.remove();
    this.$el = null;
    this.doBoot();
    this.doRender();
    this.doReady();
    if (elNext) {
      elParent.insertBefore(this.$el, elNext);
    } else {
      elParent.appendChild(this.$el);
    }
  }, // Refresh component
  show: function () {
    if (!this.$el) {
      return;
    }
    if (!this.sokeAppSelector) {
      this.$el.style.display = "block";
      return;
    }
    logDebug("component:show", this);
    if (this.overlay) {
      if (!document.querySelector(".so-modal-overlay")) {
        document.body.classList.add("so-modal-open");
        document.body.style.overflow = "hidden";
      }
      this.$overlayEl = getModalOverlay();
      this.onDestroy(function () {
        logDebug("component:show:onDestroy");
        document.body.removeChild(this.$overlayEl);

        if (!document.querySelector(".so-modal-overlay")) {
          document.body.classList.remove("so-modal-open");
          document.body.style.overflow = "auto";
        }
      });
    }
    this.sokeAppSelector.appendChild(this.$el);
    if (this.focusInput) {
      this.focusInputAction();
    }
  },
  hide: function () {
    this.$el.style.display = "none";
    if (this.$overlayEl) {
      this.$overlayEl.display = "none";
    }
    this.__lifecycle("hide");
  },
  focusInputAction() {
    setTimeout(() => {
      console.log("focusInputAction");
      this.$el
        .querySelector(
          "input:not([disabled]):not([readonly]), select:not([disabled]), textarea:not([readonly]"
        )
        ?.focus();
    }, 350);
  },
};

export function Component($component: any, $props: any, $parent: any = null) {
  if (!$component.state) {
    $component.state = {};
  }
  $props = $props ?? {};

  let component = {
    ...componentDefine,
    ...$component,
    $initState: { ...$component.state },
    $initProps: { ...$props },
    $parent: $parent,
    $app: $parent ? $parent.$app : $parent,
    $wire: $parent ? $parent.$wire : $props?.$wire,
    ...componentMixin,
  };
  let keys = Object.keys(component)
    .concat(Object.keys(component.$initState))
    .concat(Object.keys(component.$initProps))
    .concat(getMethods(component))
    .filter((item: any, index: any, items: any) => {
      return (
        ([
          "__data__",
          "__props__",
          "__hooks__",
          "__lifecycle",
          "__tapChildren",
        ].includes(item) ||
          !item.startsWith("_")) &&
        items.indexOf(item) === index
      );
    });

  return tap(
    new Proxy(component, {
      ownKeys: () => {
        return keys;
      },
      set: (target, property, value) => {
        if (target.__data__?.check(property)) {
          target.__data__.setValue(property, value);
          return true;
        }
        if (target.__props__?.check(property)) {
          target.__props__.setValue(property, value);
          return true;
        }
        if (target[property] !== undefined) {
          target[property] = value;
          return true;
        }
        return false;
      },
      get: (target, property) => {
        if (target.__data__?.check(property)) {
          return target.__data__.getValue(property);
        }
        if (target.__props__?.check(property)) {
          return target.__props__.getValue(property);
        }
        if (target[property] !== undefined) {
          return target[property];
        }
        return target[property];
      },
    }),
    (component: any) => {
      component.doInit();
    }
  );
}
