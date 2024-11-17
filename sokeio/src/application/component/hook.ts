export class Hook {
  listeners: any = {};
  component: any;
  constructor(component = undefined) {
    this.component = component ?? {};
  }

  on(event: string, callback: any) {
    if (!callback) {
      return;
    }
    console.log("on", event, callback);
    if (!this.listeners[event]) {
      this.listeners[event] = [];
    }
    this.listeners[event].push(callback);
  }

  fire(event: string, ...args: any) {
    console.log("fire", event, args);
    if (this.listeners[event]) {
      this.listeners[event].forEach((callback: any) =>
        callback.bind(this.component)(...args)
      );
    }
  }

  once(event: string, callback: any) {
    const onceCallback = (...args: any) => {
      this.off(event, onceCallback);
      callback(...args);
    };
    this.on(event, onceCallback);
  }

  off(event: string, _callback: any) {
    if (this.listeners[event]) {
      delete this.listeners[event];
    }
  }

  has(event: string) {
    return !!this.listeners[event];
  }

  destroy() {
    this.listeners = {};
  }
}
