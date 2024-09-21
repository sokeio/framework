import feature from "../feature/_index";
import { DataValue } from "./DataValue";
import { Utils } from "./Uitls";

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
  if (!components[$name] && !$parent?.components[$name]) {
    console.error("Component not found: " + $name);
    return null;
  }
  if ($parent?.components[$name]) {
    return Component($parent?.components[$name], $props, $parent);
  }
  return Component(components[$name], $props, $parent);
}
function getChildComponent(component) {
  let html = component.$el.innerHTML;

  let components = Utils.getComponentsFromText(html);
  let tempComponents = components.map((item) => {
    html = html.split(item.component).join(Utils.tagSplit);
    return getComponent(item.tag, item.attrs, component);
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
export function doBoot(component) {
  let html = component.render ? component.render() : "<div></div>";
  html = html.trim();
  component.$el = Utils.convertHtmlToElement(html);
  getChildComponent(component);
  feature(component);
  if (component.$children) {
    component.$children.forEach((item) => {
      doBoot(item);
    });
  }
  component.boot && component.boot();
}
export function doRender(component) {
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
    component.$el._sokeio = component;
  }
}
export function doReady(component) {
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
  component.$el.remove();
  component.$hookDestroy = [];
  component.$hookReady = [];
  component.$children = [];
  component.$el = null;
}
export function Component($options, $props, $parent = null) {
  let component = {
    ...$options,
    $parent: $parent,
    $children: [],
    $id: 0,
    $el: null,
    $hookDestroy: [],
    $hookReady: [],
  };
  let keys = Object.keys(component)
    .concat(Object.keys($options.state ?? {}))
    .concat(Object.keys($props))
    .concat(Utils.getMethods(component))
    .filter(fnFilter)
    .concat([
      "getId",
      "watch",
      "cleanup",
      "boot",
      "ready",
      "delete",
      "destroy",
      "onReady",
      "reRender",
      "querySelectorAll",
      "on",
      "__data__",
      "__props__",
    ])
    .filter(function (item, index, self) {
      return self.indexOf(item) === index;
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
    value: new DataValue($options.state ?? {}),
  });
  Object.defineProperty(component, "__props__", {
    value: new DataValue($props),
  });

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
          console.log(event, item, callback);
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
        this.__data__.watch(property, callback);
      }
      if (this.__props__.check(property)) {
        this.__props__.watch(property, callback);
      }
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
    },
  });
  Object.defineProperty(component, "onReady", {
    value: function ($callback) {
      if ($callback) {
        this.$hookReady.push($callback);
      }
    },
  });

  Object.defineProperty(component, "reRender", {
    value: function () {
      doDestroy(this);
      doBoot(this);
      doRender(this);
      doReady(this);
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
