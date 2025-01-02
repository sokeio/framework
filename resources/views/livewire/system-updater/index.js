export default {
  components: {},
  state: {
    isRunning: false,
  },
  register() {},
  boot() {},
  runningRender() {
    if (!this.isRunning) {
      return "";
    }
    return `
      <div class="p-3">
          <h3 class="fw-bold">System Updater</h3>
          <div class="alert alert-danger" role="alert">
              <div>
                <h4 class="alert-title">Don't Close This Page</h4>
                <div class="alert-message">System Update is running</div>
              </div>
            </div>
      </div>
      `;
  },
  render() {
    return `
      <div class="p-3">
          <h3 class="fw-bold">System Updater</h3>
         ${this.runningRender()}
      </div>
      `;
  },
};
