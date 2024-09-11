class Observable {
  listeners = {};
  state = {};
  props = {};
  constructor() {
    this.state = this.state ?? {};
    this.props = this.props ?? {};

    // Sử dụng Proxy để theo dõi sự thay đổi
    return new Proxy(this, {
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

  // Phương thức để gán giá trị và kích hoạt onChange
  onChange(property, callback) {
    this.watch(property, callback);
  }
}

export default Observable;
