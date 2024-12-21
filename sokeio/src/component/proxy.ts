import { getMethods } from "../utils";

export function proxy(component: any) {
  let keys = Object.keys(component)
    .concat(Object.keys(component.$initState))
    .concat(Object.keys(component.$initProps))
    .concat(getMethods(component))
    .filter((item: any, index: any, items: any) => {
      return (
        (["__data__", "__props__", "__hooks__"].includes(item) ||
          !item.startsWith("_")) &&
        items.indexOf(item) === index
      );
    });
  return new Proxy(component, {
    ownKeys: () => {
      return keys;
    },
    set: (target, property, value) => {
      if (target[property] !== undefined) {
        target[property] = value;
        return true;
      }
      if (target.__data__?.check(property)) {
        target.__data__.setValue(property, value);
        return true;
      }
      if (target.__props__?.check(property)) {
        target.__props__.setValue(property, value);
        return true;
      }

      return false;
    },
    get: (target, property) => {
      if (target[property] !== undefined) {
        return target[property];
      }
      if (target.__data__?.check(property)) {
        return target.__data__.getValue(property);
      }
      if (target.__props__?.check(property)) {
        return target.__props__.getValue(property);
      }

      return target[property];
    },
  });
}
