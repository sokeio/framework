let components = {};

function createComponent(name, $attrs) {
    let ops= {

    };
    Object.defineProperty(ops, "name", {
        value: name
    });
    Object.defineProperty(ops, "attrs", {
        value: $attrs
    });
    return ops;
}
export function registerComponent(name, component) {
  components[name] = component;
}
export function getComponentByName(name, $attrs) {
  if (!name || !this.components[name]) {
    console.warn({ name, $attrs });
    return null;
  }
  let component = new this.components[name]();
  component.manager = this;
  component.boot && component.boot();
  return component;
}
