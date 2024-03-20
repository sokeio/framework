export class ByteEvent {
  events: any = {};
  onSafe(event: string, callback: any) {
    this.removeListener(event, callback);
    this.on(event, callback);
  }
  /* Events */
  on(event: string, callback: any, checkEvent = true) {
    if (!callback || !event) return;
    // Check if the callback is not a function
    if (typeof callback !== "function") {
      console.error(
        `The listener callback must be a function, the given type is ${typeof callback}`
      );
      return false;
    }
    // Check if the event is not a string
    if (typeof event !== "string") {
      console.error(
        `The event name must be a string, the given type is ${typeof event}`
      );
      return false;
    }
    // Check if this event not exists
    if (this.events[event] === undefined) {
      this.events[event] = {
        listeners: [],
      };
    }
    if (checkEvent) {
      this.removeListener(event, callback);
    }
    this.events[event].listeners.push(callback);
    return true;
  }

  removeListener(event: string, callback: any) {
    // Check if this event not exists
    if (!this.events[event]) return false;

    const listeners = this.events[event].listeners;
    const listenerIndex = listeners.indexOf(callback);
    const hasListener = listenerIndex > -1;
    if (hasListener) listeners.splice(listenerIndex, 1);
    return true;
  }

  removeListenerAll(event: string) {
    // Check if this event not exists
    if (!this.events[event]) return false;

    this.events[event] = { listeners: [] };
    return true;
  }

  dispatch(event: string, details: any) {
    // Check if this event not exists
    if (this.events[event] === undefined) {
      return false;
    }
    this.events[event].listeners.forEach((listener: any) => {
      listener(details);
    });
    return true;
  }

  eventDocuments: any = {};
  onDocument(event: string, selector: string, callback: any) {
    const self = this;
    if (!self.eventDocuments[event]) self.eventDocuments[event] = {};
    self.eventDocuments[event][selector] = callback;
  }

  dispatchDocument(event: string, details: any = {}) {
    document.dispatchEvent(
      new window.Event(event, {
        bubbles: true,
        cancelable: false,
        ...(details ?? {}),
      })
    );
  }

  initEventDocument() {
    const self = this;
    Object.keys(self.eventDocuments).forEach((event) => {
      try {
        let events = self.eventDocuments[event];
        Object.keys(events).forEach(function (selector) {
          let callback = events[selector];
          document.addEventListener(event, function (ev) {
            if (ev.target) {
              let targetCurrent: any = ev.target;
              if (targetCurrent.matches(selector)) {
                callback && callback(ev);
              } else if ((targetCurrent = targetCurrent.closest(selector))) {
                callback && callback({ ...ev, target: targetCurrent });
              }
            }
          });
        });
      } catch (ex) {}
    });
  }
  uninitEventDocument() {
    const self = this;
    Object.keys(self.eventDocuments).forEach((event) => {
      try {
        let events = self.eventDocuments[event];
        Object.keys(events).forEach(function (selector) {
          let callback = events[selector];
          document.removeEventListener(event, function (ev) {
            if (ev.target) {
              let targetCurrent: any = ev.target;
              if (targetCurrent.matches(selector)) {
                callback && callback(ev);
              } else if ((targetCurrent = targetCurrent.closest(selector))) {
                callback && callback({ ...ev, target: targetCurrent });
              }
            }
          });
        });
      } catch (ex) {}
    });
  }
}
