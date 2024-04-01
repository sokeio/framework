import { MakeObject } from "./object";

export class Component {
  $el = null;
  $data = null;
  constructor(props = {}) {
    this.props = props;
  }
  $watch(property, callback) {
    this.$data.onTrigger(property, callback);
  }
  setState(value) {
    this.$data = MakeObject(value);
  }
  init() {
    // TODO document why this method 'init' is empty
  }
  render() {
    return [];
  }
}
