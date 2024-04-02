import { MakeObjectProxy } from "./proxy";

class ObjectJS {
  constructor($data) {
    this._data = $data ?? {};
    this._changeEventHandlers = {};
  }
  has(property) {
    return this._data.hasOwnProperty(property);
  }
  get(property) {
    return this._data[property];
  }

  set(property, value) {
    const oldValue = this._data[property];
    this._data[property] = value;
    let self = this;
    setTimeout(() => {
      self.doTrigger(property, oldValue, value);
    });
  }
  watch(property, callback) {
    this.onChange(property, callback);
    return this;
  }
  onChange(property, handler) {
    if (!this._changeEventHandlers[property]) {
      this._changeEventHandlers[property] = [];
    }
    this._changeEventHandlers[property].push(handler);
  }
  removeTriggerAll(property) {
    if (this._changeEventHandlers[property]) {
      this._changeEventHandlers[property] = [];
    }
  }
  removeTrigger(property, handler) {
    if (this._changeEventHandlers[property]) {
      this._changeEventHandlers[property] = this._changeEventHandlers[
        property
      ].filter((h) => h !== handler);
    }
  }

  doTrigger(property, oldValue, newValue) {
    if (this._changeEventHandlers[property]) {
      this._changeEventHandlers[property].forEach((handler) => {
        handler(oldValue, newValue, property);
      });
    }
  }
  toString() {
    return JSON.stringify(this._data);
  }
}
export function MakeObject($data) {
  return MakeObjectProxy(new ObjectJS($data ?? {}));
}
