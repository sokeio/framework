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
export function getComponent($name, $props) {
  return Component(components[$name], $props);
}
export function Component($options, $props) {
  let component = {
    ...$options,
  };
  Object.defineProperty(component, "__data__", {
    value: new DataValue($options.state),
  });
  Object.defineProperty(component, "__props__", {
    value: new DataValue($props),
  });

  Object.defineProperty(component, "id", {
    value: ++number,
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
  return new Proxy(component, {
    ownKeys: (target) => {
      return target.__data__
        .getKeys()
        .concat(target.__props__.getKeys())
        .concat(Utils.getMethods(target))
        .concat(Object.keys(target))
        .filter(fnFilter);
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
