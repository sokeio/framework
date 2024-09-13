import { Utils } from "./Uitls";

class Observable {
  listeners = {};
  removeListeners = [];
  state = {};
  props = {};
  constructor() {
    this.state = this.state ?? {};
    this.props = this.props ?? {};

    // Sử dụng Proxy để theo dõi sự thay đổi
    return new Proxy(this, {
      ownKeys(target) {
        return Object.keys(target.state)
          .concat(Object.keys(target.props))
          .concat(Object.keys(target))
          .concat(Utils.getMethods(target))
          .filter((item, index, items) => {
            return items.indexOf(item) === index;
          })
          .filter(
            (item) =>
              ![
                "elApp",
                "state",
                "props",
                "listeners",
                "removeListeners",
                "number",
              ].includes(item)
          );
      },
      set: (target, property, value) => {
        if (property in target.state) {
          const oldValue = target.state[property];
          target.state[property] = value;

          // Kích hoạt onChange nếu có listener
          setTimeout(() => {
            target.applyProperty(property, value, oldValue);
          }, 1);
          return true; // Trả về true để xác nhận thay đổi
        }
        if (property in target.props) {
          const oldValue = target.props[property];
          target.props[property] = value;

          // Kích hoạt onChange nếu có listener
          setTimeout(() => {
            target.applyProperty(property, value, oldValue);
          }, 1);
          return true; // Trả về true để xác nhận thay đổi
        }
        target[property] = value; // Trả về undefined nếu không tìm thể
        return true;
      },
      get: (target, property) => {
        if (property in target.state) {
          return target.state[property];
        }
        if (property in target.props) {
          return target.props[property];
        }
        return target[property]; // Trả về undefined nếu không tìm thấy
      },
    });
  }
  cleanup(callback) {
    this.removeListeners.push(callback);
  }
  // Phương thức để đăng ký listener
  watch(property, callback) {
    if (!this.listeners[property]) {
      this.listeners[property] = [];
    }
    this.listeners[property].push(callback);
  }
  applyProperty(property, value, oldValue) {
    if (this.listeners[property]) {
      this.listeners[property].forEach((callback) => callback(value, oldValue));
    }
  }
  applyCleanup() {
    this.removeListeners.forEach((callback) => callback());
    this.removeListeners = [];
    this.listeners = {};
  }

  // Phương thức để gán giá trị và kích hoạt onChange
  onChange(property, callback) {
    this.watch(property, callback);
  }
}

export default Observable;
