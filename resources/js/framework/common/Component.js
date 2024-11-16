import feature from "../feature/_index";
import request from "../request";
import { DataValue } from "./DataValue";
import { Utils, logDebug, getModalOverlay } from "./Uitls";
export const $request = request;

let number = new Date().getTime();
let components = {};
let fnFilter = (item, index, items) => {
  return !item.startsWith("_") && items.indexOf(item) === index;
};
export function registerComponent(name, component) {
  components[name] = component;
}
export function getComponents() {
  return components;
}
export function getComponent($name, $props, $parent = null) {
  if (
    !components[$name] &&
    !$parent?.components[$name] &&
    !$parent?.$root?.components[$name]
  ) {
    console.error("Component not found: " + $name);
    return null;
  }
  if ($parent?.components[$name]) {
    return Component($parent?.components[$name], $props, $parent);
  }
  if ($parent?.$root?.components[$name]) {
    return Component($parent?.$root?.components[$name], $props, $parent);
  }
  return Component(components[$name], $props, $parent);
}
function getChildComponent(component) {
  let html = component.$el.innerHTML;

  let components = Utils.getComponentsFromText(html);
  let tempComponents = components.map((item) => {
    html = html.split(item.component).join(Utils.tagSplit);
    let childComponent = getComponent(item.tag, item.attrs, component);
    if (childComponent) {
      doRegister(childComponent);
    }
    return childComponent;
  });
  if (tempComponents.length) {
    let templHtml = "";
    html.split(Utils.tagSplit).forEach((item, index) => {
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
    component.$children = tempComponents;
  }

  component.$el.innerHTML = html;

  return component;
}
export function doRegister(component) {
  logDebug("doRegister", component);
  component.register && component.register();
}
export function doBoot(component) {
  logDebug("doBoot", component);
  component.boot && component.boot();
  let html = component.render ? component.render() : "<div></div>";
  html = html.trim();
  if (component.$el) {
    component.$el.innerHTML = html;
  } else {
    component.$el = Utils.convertHtmlToElement(html);
  }
  feature(component);
  getChildComponent(component);
  if (component.$children) {
    component.$children.forEach((item) => {
      doBoot(item);
    });
  }
}
export function doRender(component) {
  logDebug("doRender", component);
  if (component.$children) {
    component.$children.forEach((item) => {
      let elTemp = component.$el.querySelector(
        "#sokeio-component-" + item.getId()
      );
      elTemp.parentNode.insertBefore(item.$el, elTemp);
      elTemp.remove();
      doRender(item);
    });
  }
  if (component.$el) {
    component.$el.setAttribute("data-sokeio-id", component.getId());
    component.$el.__sokeio = component;
    // var mutationObserver = new MutationObserver(function (mutations) {
    //   feature(component);
    // });
    // mutationObserver.observe(document.documentElement, {
    //   childList: true,
    //   subtree: true,
    // });
    // component.cleanup(() => {
    //   mutationObserver.disconnect();
    // });
  }
}
export function doReady(component) {
  logDebug("doReady", component);
  if (component.$children) {
    component.$children.forEach((item) => {
      doReady(item);
    });
  }
  component.ready && component.ready();
  if (component.$hookReady) {
    component.$hookReady.forEach((fn) => {
      fn.bind(component)();
    });
  }
}
export function doDestroy(component) {
  logDebug("doDestroy", component);
  if (component.$children) {
    component.$children.forEach((item) => {
      doDestroy(item);
    });
  }
  component.destroy && component.destroy();
  if (component.$hookDestroy) {
    component.$hookDestroy.forEach((fn) => {
      fn.bind(component)();
    });
  }
  component.$el?.remove();
  component.$hookDestroy = [];
  component.$hookReady = [];
  component.$children = [];
  component.$el = null;
  component.state = {};
  component = undefined;
}
export function Component($component, $props, $parent = null) {
  if (!$component.state) {
    $component.state = {};
  }
  let initState = {};
  try {
    initState = { ...$component.state };
  } catch (err) {
    console.error(err);
  }
  let component = {
    ...$component,
    $initState: { ...initState },

    $parent: $parent,

    $children: [],
    $id: 0,
    $el: null,
    $hookDestroy: [],
    $hookReady: [],
    $root: $parent ? $parent.$root : $parent,
    $wire: $parent ? $parent.$wire : $props.$wire,
  };
  let keys = Object.keys(component)
    .concat(Object.keys(initState))
    .concat(Object.keys($props))
    .concat(Utils.getMethods(component))
    .filter(fnFilter)
    .concat([
      "getId",
      "watch",
      "cleanup",
      "show",
      "boot",
      "ready",
      "delete",
      "destroy",
      "onReady",
      "reRender",
      "querySelectorAll",
      "on",
      "$request",
      "$root",
      "__data__",
      "__props__",
    ])
    .filter(function (item, index, self) {
      return self.indexOf(item) === index;
    });
  Object.defineProperty(component, "$request", {
    value: $request,
  });
  Object.defineProperty(component, "getId", {
    value: function () {
      if (!this.$id) {
        this.$id = ++number;
      }
      return this.$id;
    },
  });
  Object.defineProperty(component, "__data__", {
    value: new DataValue({
      ...component.$initState,
    }),
  });
  Object.defineProperty(component, "__props__", {
    value: new DataValue({
      ...$props,
    }),
  });
  if (component.sokeAppSelector) {
    Object.defineProperty(component, "show", {
      value: function () {
        if (this.$el) {
          if (this.overlay) {
            if (!document.querySelector(".so-modal-overlay")) {
              document.body.classList.add("so-modal-open");
              document.body.style.overflow = "hidden";
            }

            let html = getModalOverlay();
            document.body.appendChild(html);
            this.cleanup(() => {
              document.body.removeChild(html);
              if (!document.querySelector(".so-modal-overlay")) {
                document.body.classList.remove("so-modal-open");
                document.body.style.overflow = "auto";
              }
            });
          }
          component.sokeAppSelector.appendChild(this.$el);
        }
      },
    });
  }

  Object.defineProperty(component, "querySelectorAll", {
    value: function (selector, callback) {
      this.onReady(function () {
        if (this.$el) {
          let arr = [...this.$el.querySelectorAll(selector)];
          if (callback) {
            callback.bind(this)(arr);
          }
          return arr;
        }
      });
    },
  });
  Object.defineProperty(component, "on", {
    value: function (selector, event, callback) {
      this.querySelectorAll(selector, (arr) => {
        arr.forEach((item) => {
          logDebug(event, item, callback);
          item.addEventListener(event, callback);
          this.$hookDestroy.push(() => {
            item.removeEventListener(event, callback);
          });
        });
      });
    },
  });
  Object.defineProperty(component, "watch", {
    value: function (property, callback) {
      if (this.__data__.check(property)) {
        this.__data__.watch(property, callback.bind(this));
      }
      if (this.__props__.check(property)) {
        this.__props__.watch(property, callback.bind(this));
      }
      return this;
    },
  });

  Object.defineProperty(component, "delete", {
    value: function () {
      doDestroy(this);
    },
  });
  Object.defineProperty(component, "cleanup", {
    value: function ($callback) {
      if ($callback) {
        this.$hookDestroy.push($callback);
      }
      return this;
    },
  });
  Object.defineProperty(component, "onReady", {
    value: function ($callback) {
      if ($callback) {
        this.$hookReady.push($callback);
      }
      return this;
    },
  });

  Object.defineProperty(component, "reRender", {
    value: function () {
      let elParent = this.$el.parentNode;
      let elNext = this.$el.nextSibling;
      this.$el.remove();
      this.$el = null;
      logDebug("reRender", this);
      doBoot(this);
      doRender(this);
      doReady(this);
      if (elNext) {
        elParent.insertBefore(this.$el, elNext);
      } else {
        elParent.appendChild(this.$el);
      }
      return this;
    },
  });

  return new Proxy(component, {
    ownKeys: () => {
      return keys;
    },
    set: (target, property, value) => {
      if (target.__data__.check(property)) {
        target.__data__.setValue(property, value);
        return true;
      }
      if (target.__props__.check(property)) {
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
      if (target.__data__.check(property)) {
        return target.__data__.getValue(property);
      }
      if (target.__props__.check(property)) {
        return target.__props__.getValue(property);
      }
      if (target[property] !== undefined) {
        return target[property];
      }
      return target[property];
    },
  });
}
