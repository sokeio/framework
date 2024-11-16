export class DataValue {
  listeners: any = {};
  removeListeners: any = [];
  data: any = {};
  constructor(data = undefined) {
    this.data = data ?? {};
  }
  getValue(key: string) {
    return this.data[key];
  }
  setValue(key: string, value: any) {
    const oldValue = this.data[key];
    setTimeout(() => {
      this.changeProperty(key, value, oldValue);
    }, 1);
    this.data[key] = value;
  }
  watch(property: string, callback: any) {
    if (!this.listeners[property]) {
      this.listeners[property] = [];
    }
    this.listeners[property].push(callback);
  }
  changeProperty(property: string, value: any, oldValue: any) {
    if (this.listeners[property]) {
      this.listeners[property].forEach((callback: any) =>
        callback(value, oldValue)
      );
    }
  }
  cleanup() {
    this.removeListeners.forEach((callback: any) => callback());
    this.removeListeners = [];
    this.listeners = {};
  }
  check(property: string) {
    return this.data[property] !== undefined;
  }
  getKeys() {
    return Object.keys(this.data);
  }
}
