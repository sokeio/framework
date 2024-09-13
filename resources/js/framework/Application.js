import Component from "./Component";
import { Demo } from "./components/demo";
import { Demo2 } from "./components/demo2";

class Application extends Component {
  components = {
    "sokeio::demo": Demo,
    "sokeio::demo2": Demo2,
  };
  template = null;
  registerComponent(name, component) {
    this.components[name] = component;
  }
  getComponentByName(name, $attrs) {
    if (!name || !this.components[name]) {
      console.warn({ name, $attrs });
      return null;
    }
    let component = new this.components[name]();
    component.manager = this;
    component.boot && component.boot();
    return component;
  }
  render() {
    if (this.template) {
      return this.template;
    }
    return `<div>Tesst [sokeio::demo /] </div>`;
  }
  run(querySelectorOrEl = null, template = null) {
    if (!querySelectorOrEl) {
      querySelectorOrEl = document.body;
    }
    if (typeof querySelectorOrEl === "string") {
      querySelectorOrEl = document.querySelector(querySelectorOrEl);
    }
    this.manager = this;
    this.el = null;
    this.template = template;
    this.elApp = querySelectorOrEl;
    this.renderComponent();
  }
}

export default Application;
