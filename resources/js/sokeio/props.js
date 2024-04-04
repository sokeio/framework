import { BaseJS } from "./base";

export class Props extends BaseJS {
  constructor() {
    super();
  }
  current = null;
  __eInit = [];
  __eReady = [];
  setCurrent($current) {
    this.current = $current;
    let self = this;
    this.__eInit.forEach(($callback) => {
      this.current.onInit(() => {
        $callback.bind(self)();
      });
    });
    this.__eReady.forEach(($callback) => {
      this.current.onReady(() => {
        $callback.bind(self)();
      });
    });
    return this;
  }
  setAttrs(state) {
    let self = this;
    this.state = state;
    this.onReady(() => {
      Object.keys(self.state).forEach((key) => {
        if (this.parent.checkProperty(this.state[key])) {
          this.set(key, this.parent.get(this.state[key]));
          this.parent.watch(this.state[key], (value) => {
            this.set(key, value);
          });
        } else {
          this.set(key, this.state[key]);
        }
      });
    });
    return this;
  }
  onReady($callback) {
    this.__eReady.push($callback);
    return this;
  }
  onInit($callback) {
    this.__eInit.push($callback);
    return this;
  }
}
