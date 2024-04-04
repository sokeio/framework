export class BaseJS {
  $_dataValue = {};
  $_dataValueChangeEvents = {};
  $_dataEvents = {};
  parent = null;
  state = {};
  clearBase() {
    this.$_dataValue = {};
    this.$_dataValueChangeEvents = {};
    this.$_dataEvents = {};
    this.parent = null;
  }
  setParent($parent) {
    this.parent = $parent;
    return this;
  }
  initState() {
    this.ressetState();
    return this;
  }
  ressetState() {
    if (!this.state) this.state = {};
    Object.keys(this.state).forEach((key) => {
      this.set(key, this.state[key]);
    });
  }
  checkProperty(property) {
    if (property in this.$_dataValue) return true;
    return false;
  }
  get(property) {
    if (property in this) return this[property];
    return this.$_dataValue[property];
  }
  set(property, value) {
    if (property in this) {
      this[property] = value;
      return;
    }
    let oldValue = this.$_dataValue[property];
    this.$_dataValue[property] = value;
    let self = this;
    setTimeout(() => {
      self.doChangeProperty(property, oldValue, value);
    });
  }
  doChangeProperty(property, oldValue, newValue) {
    if (this.$_dataValueChangeEvents[property]) {
      this.$_dataValueChangeEvents[property].forEach((handler) => {
        handler(newValue, oldValue, property);
      });
    }
  }
  onChangeProperty(property, handler) {
    if (!this.$_dataValueChangeEvents[property]) {
      this.$_dataValueChangeEvents[property] = [];
    }
    this.$_dataValueChangeEvents[property].push(handler);
  }
  removeChangeProperty(property, handler) {
    if (this.$_dataValueChangeEvents[property]) {
      this.$_dataValueChangeEvents[property] = this.$_dataValueChangeEvents[
        property
      ].filter((h) => h !== handler);
    }
  }
  removeChangePropertyAll(property) {
    if (this.$_dataValueChangeEvents[property]) {
      this.$_dataValueChangeEvents[property] = [];
    }
  }
  watch(property, callback, destroy) {
    if (!Array.isArray(property)) property = [property];
    property.forEach((p) => {
      this.onChangeProperty(p, callback);
    });
    if (destroy) {
      destroy.bind(this)(() => {
        property.forEach((p) => {
          this.removeChangeProperty(p, callback);
        });
      });
    }
    return this;
  }
  static make() {
    let $inst = new this();
    const arrFuncs = [
      "get",
      "set",
      "has",
      "state",
      "initState",
      "onInit",
      "$_dataValue",
      "checkProperty",
      "$props",
    ];
    if ($inst["onInit"]) {
      $inst.onInit(function () {
        $inst.initState();
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
          return target.checkProperty.bind(target)(property);
        }
        return false;
      },
    });
    return $proxy;
  }
}
