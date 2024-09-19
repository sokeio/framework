export class DataValue {
  listeners = {};
  removeListeners = [];
  data = {};
  constructor(data = undefined) {
    this.data = data ?? {};
  }
  getValue($key) {
    return this.data[$key];
  }
  setValue($key, value) {
    const oldValue = this.data[$key];
    setTimeout(() => {
      this.changeProperty($key, value, oldValue);
    }, 1);
    this.data[$key] = value;
  }
  watch(property, callback) {
    if (!this.listeners[property]) {
      this.listeners[property] = [];
    }
    this.listeners[property].push(callback);
  }
  changeProperty(property, value, oldValue) {
    if (this.listeners[property]) {
      this.listeners[property].forEach((callback) => callback(value, oldValue));
    }
  }
  cleanup() {
    this.removeListeners.forEach((callback) => callback());
    this.removeListeners = [];
    this.listeners = {};
  }
  check(property) {
    return this.data[property] !== undefined;
  }
  getKeys() {
    return Object.keys(this.data);
  }
}
