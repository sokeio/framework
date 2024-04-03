export class BaseJS {
  ____value = {};
  ____valueChangeEvents = {};
  ____events = {};
  ____parent = null;
  setParent($parent) {
    this.____parent = $parent;
    return this;
  }
  initState() {
    this.____value = this.state;
    this.ressetState();
    return this;
  }
  ressetState() {
    if (!this.state) this.state = {};
    Object.keys(this.state).forEach((key) => {
      this.set(key, this.state[key]);
    });
  }
  get(property) {
    if (this[property]) return this[property];
    return this.____value[property];
  }
  set(property, value) {
    if (this[property] !== undefined) {
      this[property] = value;
      return;
    }
    let oldValue = this.____value[property];
    this.____value[property] = value;
    let self = this;
    console.log("doChangeProperty1", property, oldValue, value);
    setTimeout(() => {
      self.doChangeProperty(property, oldValue, value);
    });
  }
  doChangeProperty(property, oldValue, newValue) {
    console.log("doChangeProperty", property, oldValue, newValue);
    if (this.____valueChangeEvents[property]) {
      this.____valueChangeEvents[property].forEach((handler) => {
        handler(oldValue, newValue, property);
      });
    }
  }
  onChangeProperty(property, handler) {
    if (!this.____valueChangeEvents[property]) {
      this.____valueChangeEvents[property] = [];
    }
    this.____valueChangeEvents[property].push(handler);
  }
  removeChangeProperty(property, handler) {
    if (this.____valueChangeEvents[property]) {
      this.____valueChangeEvents[property] = this.____valueChangeEvents[
        property
      ].filter((h) => h !== handler);
    }
  }
  removeChangePropertyAll(property) {
    if (this.____valueChangeEvents[property]) {
      this.____valueChangeEvents[property] = [];
    }
  }
  watch(property, callback) {
    this.onChangeProperty(property, callback);
    return this;
  }
  static make() {
    let $inst = new this();
    const arrFuncs = ["get", "set", "has", "initState", "____value"];
    if ($inst["onReady"]) {
      $inst.onReady(function () {
        $inst.initState();
        console.log("onReady:initState");
      });
    } else {
      $inst.initState();
    }
    let $proxy = new Proxy($inst, {
      get: (target, property) => {
        if (typeof property === "string") {
          if (typeof $inst[property] === "function") {
            if (arrFuncs.includes(property)) {
              return target[property].bind($inst);
            }
            return target[property].bind($proxy);
          }
          return target.get.bind(target)(property);
        }
      },
      set: (target, property, value) => {
        if (typeof property === "string") {
          target.set.bind(target)(property, value);
          return true;
        }
        return false;
      },
      has: (target, property) => {
        if (typeof property === "string") {
          return target.has.bind(target)(property);
        }
        return false;
      },
    });
    return $proxy;
  }
}
