import Component from "./Component";

class Application extends Component {
  components = {};
  registerComponent(name, component) {
    this.components[name] = component;
  }
  getComponentByName(name, $attrs, parent) {
    if (!name || !this.components[name]) {
      console.warn({ name, $attrs, parent, isAddChild });
      return null;
    }

    let component = new this.components[name]();
    component.manager = this;
    component.parent = parent;
    return component;
  }
  render() {
    return "";
  }
}

export default Application;
