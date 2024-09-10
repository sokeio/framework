class Application extends Component {
  number = 1000;
  constructor() {
    this.number = new Date().getTime();
    this.components = {};
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
