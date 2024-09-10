import Component from "./Component";

class Application extends Component {
  number = 1000;
  number = new Date().getTime();
  components = {};
  constructor() {
    return super();
  }
  nextId() {
    return ++this.number;
  }
  registerComponent(name, component) {
    this.components[name] = component;
  }
  render() {
    return "";
  }
}

export default Application;
