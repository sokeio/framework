import { logDebug, tap } from "./utils";
import {
  destroy,
  getComponents,
  getComponentsFromText,
  getModalOverlay,
  tagSplit,
} from "./common";
import { DataValue } from "./datavalue";
import { Hook } from "./hook";
import request from "./request";

let number = new Date().getTime();
function getMethods(obj: any): any[] {
  const isGetter = (x: any, name: any) =>
    (Object.getOwnPropertyDescriptor(x, name) || {}).get;
  const isFunction = (x: any, name: any) => typeof x[name] === "function";
  const deepFunctions: any = (x: any) =>
    x &&
    x !== Object.prototype &&
    Object.getOwnPropertyNames(x)
      .filter((name) => isGetter(x, name) || isFunction(x, name))
      .concat(deepFunctions(Object.getPrototypeOf(x)) || []);
  const distinctDeepFunctions = (x: any) =>
    Array.from(new Set(deepFunctions(x)));
  const userFunctions = (x: any) =>
    distinctDeepFunctions(x).filter(
      (name: any) => name !== "constructor" && !~name.indexOf("__")
    );
  return userFunctions(obj);
}

export function Component($component: any, $props: any, $parent: any = null) {
  if (!$component.state) {
    $component.state = {};
  }
  let initState = {};
  try {
    initState = { ...$component.state };
  } catch (err) {
    console.error(err);
  }
  let fnFilter = (item: any, index: any, items: any) => {
    return !item.startsWith("_") && items.indexOf(item) === index;
  };
  let component = {
    ...$component,
    $initState: { ...initState },

    $parent: $parent,

    $children: [],
    $id: 0,
    $el: null,
    $app: $parent ? $parent.$app : $parent,
    $wire: $parent ? $parent.$wire : $props?.$wire,
    __hooks__: null,
    reRender: null,
    delete: null,
  };
  let keys = Object.keys(component)
    .concat(Object.keys(initState))
    .concat(Object.keys($props))
    .concat(getMethods(component))
    .filter(fnFilter)
    .concat([
      "getId",
      "watch",
      "cleanup",
      "show",
      "boot",
      "ready",
      "delete",
      "closeApp",
      "destroy",
      "onReady",
      "reRender",
      "querySelectorAll",
      "on",
      "$request",
      "$root",
      "$app",
      "$wire",
      "__hooks__",
      "__data__",
      "__props__",
    ])
    .filter(function (item, index, self) {
      return self.indexOf(item) === index;
    });
  Object.defineProperty(component, "$request", {
    value: request,
  });
  Object.defineProperty(component, "getId", {
    value: function () {
      if (!this.$id) {
        this.$id = ++number;
      }
      return this.$id;
    },
  });
  Object.defineProperty(component, "__hooks__", {
    value: new Hook(component),
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
    value: function (selector: any, callback: any) {
      let self: any = this;
      this.onReady(function () {
        if (self.$el) {
          let arr = [...self.$el.querySelectorAll(selector)];
          if (callback) {
            callback.bind(self)(arr);
          }
          return arr;
        }
      });
    },
  });
  Object.defineProperty(component, "on", {
    value: function (selector: any, event: any, callback: any) {
      this.querySelectorAll(selector, (arr: any) => {
        arr.forEach((item: any) => {
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
    value: function (property: any, callback: any) {
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
      destroy(this);
    },
  });
  Object.defineProperty(component, "closeApp", {
    value: function () {
      console.log("closeApp");
      this.$root.delete();
    },
  });
  Object.defineProperty(component, "cleanup", {
    value: function ($callback: any) {
      this.__hooks__.on("destroy", $callback);
      return this;
    },
  });
  Object.defineProperty(component, "onReady", {
    value: function ($callback: any) {
      this.__hooks__.on("ready", $callback);
      return this;
    },
  });

  return tap(
    new Proxy(component, {
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
    }),
    (target: any) => {
      target.__hooks__ = new Hook(target);
    }
  );
}

export function getComponent($name: string, $props: any, $parent: any = null) {
  let components = getComponents();
  if (
    !components[$name] &&
    !$parent?.components[$name] &&
    !$parent?.$app?.components[$name]
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

export function processChildrenInComponent(component: any) {
  let html = component.$el.innerHTML;

  let components = getComponentsFromText(html);
  let tempComponents: any = components.map((item) => {
    html = html.split(item.component).join(tagSplit);
    let childComponent = getComponent(item.tag, item.attrs, component);
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
    component.$children = tempComponents;
  }

  component.$el.innerHTML = html;

  return component;
}
