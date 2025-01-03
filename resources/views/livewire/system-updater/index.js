export default {
  components: {},
  state: {
    isRunning: false,
    productId: "",
    productName: "",
    productVersion: "",
    framework: "",
    modules: [],
    themes: [],
    isUpdated: false,
  },
  register() {
    let self = this;
    let productInfo = this.$wire.productInfo;
    self.productId = productInfo.product_id;
    self.productName = productInfo.product_name;
    self.productVersion = productInfo.product_version;
    self.framework = productInfo.framework;
    self.modules = productInfo.modules;
    self.themes = productInfo.themes;
    self.refresh();
  },
  boot() {},
  runningRender() {
    if (!this.isRunning) {
      return "";
    }
    return `
      <div class="alert alert-danger mt-2" role="alert">
              <div>
                <h4 class="alert-title">Don't Close This Page</h4>
                <div class="alert-message">System Update is running</div>
              </div>
            </div>
      `;
  },
  listRender(items, title) {
    if (items.length == 0) {
      return "";
    }
    let html = `<h5 class="list-group-header sticky-top bg-cyan text-bg-cyan">${title}</h5>`;
    items.forEach(function (item) {
      html += `<div class="list-group-item p-2">
                  <div class="row  g-1 align-items-center">
                    <div class="col-auto"><span class="badge bg-red"></span></div>
                    <div class="col text-truncate">
                      <a href="#" class="text-reset d-block">${item.name}</a>
                    </div>
                    <div class="col-auto">
                      <div class="d-block text-secondary text-truncate mt-n1">${item.version} -> ${item.version}</div>
                    </div>
                  </div>  
              </div>`;
    });
    return html;
  },
  productRender() {
    let html = "";
    html += ` <div class="card">
                      <div class="card-header p-2">
                        <h3 class="card-title">${this.productName} 
                        <span class="badge bg-red text-bg-red">${
                          this.productVersion
                        }</span></h3>
                      </div>
                      <div class="card-body p-0">
                        <div class="fw-bold bg-primary text-bg-primary p-2 mb-1">Sokeio Framework: ${
                          this.framework
                        }</div>
                        <div class="list-group list-group-flush overflow-auto">
                          ${this.listRender(this.modules, "Modules")}
                          ${this.listRender(this.themes, "Themes")} 
                        </div>
                      </div>
                    </div>`;
    return html;
  },
  buttonRender() {
    if (this.isRunning) {
      return "";
    }
    let html = "";
    html += `
      <div class="d-flex justify-content-center mt-2">
      <button class="btn btn-primary p-2" so-on:click="updateNow()"> <i class="ti ti-refresh me-1"></i> Update Now</button>
      </div>
    `;
    return html;
  },
  updateNow() {
    this.isRunning = true;
    this.$app.$el.querySelector(".so-modal-close").style.display = "none";
    this.refresh();
  },

  render() {
    return `
      <div class="p-3">
          <h3 class="fw-bold">System Updater</h3>
          ${this.productRender()}
          ${this.buttonRender()}
          ${this.runningRender()}
      </div>
      `;
  },
};
