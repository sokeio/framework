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
    return property in this.$_dataValue;
  }
  get(property) {
    if (property in this) return this[property];
    let arrs = this.getPropertySplit(property);
    let value = this.$_dataValue[arrs[0]];
    if (arrs.length > 1) {
      for (let i = 1; i < arrs.length; i++) {
        if (value) {
          value = value[arrs[i]];
        }
      }
    }
    // console.log({ arrs, value, data: this.$_dataValue[arrs[0]] });
    return value;
  }
  set(property, value) {
    if (property in this) {
      this[property] = value;
      return;
    }
    let arrs = this.getPropertySplit(property);
    let oldValue = this.$_dataValue[arrs[0]];
    if (arrs.length > 1) {
      for (let i = 1; i < arrs.length; i++) {
        if (oldValue) {
          oldValue = value[arrs[i]];
        } else {
          break;
        }
      }
    }
    let valueTemp = this.$_dataValue[arrs[0]] ?? {};
    if (arrs.length > 1) {
      let _valueTemp = valueTemp;
      for (let i = 1; i < arrs.length; i++) {
        if (i === arrs.length - 1) {
          _valueTemp[arrs[i]] = value;
        } else {
          if (_valueTemp[arrs[i]]) {
            _valueTemp = _valueTemp[arrs[i]];
          } else {
            _valueTemp[arrs[i]] = {};
          }
        }
      }
      this.$_dataValue[arrs[0]] = valueTemp;
    } else {
      this.$_dataValue[arrs[0]] = value;
    }
    setTimeout(() => this.doChangeProperty(property, oldValue, value), 0);
  }
  getPropertySplit(property, split = ".") {
    return property.split(split);
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
      this.onChangeProperty(this.getPropertySplit(p)[0], callback);
    });
    if (!destroy && this.onDestroy) destroy = this.onDestroy;
    if (destroy) {
      destroy.bind(this)(() => {
        property.forEach((p) => {
          this.removeChangeProperty(this.getPropertySplit(p)[0], callback);
        });
      });
    }
    return this;
  }
  getArrayFuncs() {
    return [
      "get",
      "set",
      "has",
      "state",
      "initState",
      "onInit",
      "$_dataValue",
      "checkProperty",
      "$props",
      "getPropertySplit",
    ];
  }
  static make() {
    let $inst = new this();
    const arrFuncs = $inst.getArrayFuncs();
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
              if (property === "$axios") {
                return target[property];
              }
              return target[property].bind($inst);
            }
            return target[property].bind($proxy);
          }
          if (arrFuncs.includes(property)) {
            return target.get.bind($inst)(property);
          } else {
            return target.get.bind(target)(property);
          }
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
