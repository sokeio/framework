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
    this.listeners[event] = callback;
  }

  fire(event: string, ...args: any) {
    if (this.listeners[event]) {
      this.listeners[event].bind(this.component)(...args);
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
