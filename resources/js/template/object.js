class ObjectJS {
  constructor($data) {
    this._data = $data ?? {};
    this._changeEventHandlers = {};
  }

  get(property) {
    return this._data[property];
  }

  set(property, value) {
    const oldValue = this._data[property];
    this._data[property] = value;

    this.doTrigger(property, oldValue, value);
  }

  onTrigger(property, handler) {
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
        handler(property, oldValue, newValue);
      });
    }
  }
  toString() {
    return JSON.stringify(this._data);
  }
}
export function MakeObject($data) {
  return new Proxy(new ObjectJS($data ?? {}), {
    get: (target, property) => {
      if (typeof property === "string") {
        return this.get(property);
      }
    },
    set: (target, property, value) => {
      if (typeof property === "string") {
        this.set(property, value);
        return true;
      }
      return false;
    },
  });
}
