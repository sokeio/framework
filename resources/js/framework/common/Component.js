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
  if (!components[$name]) {
    console.error("Component not found: " + $name);
    return null;
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
export function Component($options, $props, $parent = null) {
  let component = {
    ...$options,
    $parent: $parent,
    $children: [],
    $id: 0,
    $el: null,
    $manager: null,
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
      "doBoot",
      "doRender",
      "doUpdate",
      "doDestroy",
      "doReady",
      "__data__",
      "__props__",
    ]);
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

  Object.defineProperty(component, "cleanup", {
    value: function (property, callback) {
      this.__data__.cleanup(property, callback);
    },
  });

  Object.defineProperty(component, "doBoot", {
    value: function () {
      let html = this.render ? this.render() : "<div></div>";
      html = html.trim();
      this.$el = Utils.convertHtmlToElement(html);
      getChildComponent(this);
      feature(this);
      if (this.$children) {
        this.$children.forEach((item) => {
          item.doBoot();
          item.boot && item.boot();
        });
      }
    },
  });

  Object.defineProperty(component, "doRender", {
    value: function () {
      if (this.$children) {
        this.$children.forEach((item) => {
          let elTemp = this.$el.querySelector(
            "#sokeio-component-" + item.getId()
          );
          elTemp.parentNode.insertBefore(item.$el, elTemp);
          elTemp.remove();
          item.doRender();
        });
      }
      // if (this.$el) {
      //   this.$el.setAttribute("data-sokeio-id", this.getId());
      //   this.$el._sokeio = this;
      // }
    },
  });
  Object.defineProperty(component, "doReady", {
    value: function () {
      if (this.$children) {
        this.$children.forEach((item) => {
          item.doReady();
        });
      }
    },
  });

  Object.defineProperty(component, "doDestroy", {
    value: function () {
      if (this.$children) {
        this.$children.forEach((item) => {
          item.doDestroy();
          item.destroy && item.destroy();
        });
      }
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
