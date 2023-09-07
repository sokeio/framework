export class PlatformEvent {
  $events = {};
  onSafe(event, callback) {
    this.removeListener(event, callback);
    this.on(event, callback);
  }
  /* Events */
  on(event, callback, checkEvent = true) {
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
    if (this.$events[event] === undefined) {
      this.$events[event] = {
        listeners: [],
      };
    }
    if (checkEvent) {
      this.removeListener(event, callback);
    }
    this.$events[event].listeners.push(callback);
  }

  removeListener(event, callback) {
    // Check if this event not exists

    if (!this.$events[event]) return false;

    const listeners = this.$events[event].listeners;
    const listenerIndex = listeners.indexOf(callback);
    const hasListener = listenerIndex > -1;
    if (hasListener) listeners.splice(listenerIndex, 1);
  }

  removeListenerAll(event) {
    // Check if this event not exists

    if (!this.$events[event]) return false;

    this.$events[event] = { listeners: [] };
  }

  dispatch(event, details) {
    // Check if this event not exists
    if (this.$events[event] === undefined) {
      return false;
    }
    this.$events[event].listeners.forEach((listener) => {
      listener(details);
    });
  }
}
