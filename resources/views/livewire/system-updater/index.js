let updatingComponent = {
  state: {
    process: -1,
    message: "",
    isShow: false,
  },
  register() {
    this.$parent.$updatingComponent = this;
  },
  processUpdate($process, $message) {
    if (this.process != $process) {
      this.process = $process;
      this.message = " (" + $process + "%)" + $message + "<br/>" + this.message;
      this.isShow = true;
      this.refresh(0);
    }
  },
  render() {
    if (!this.isShow) {
      return `<div style="display:none"></div>`;
    }
    return `
    <div class="sokeio-updating-component">
      <div class="p-3 mt-2" >
        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="${this.process}" aria-valuemin="0" aria-valuemax="100" style="width: ${this.process}%"></div>
        </div>
      <div class="alert alert-danger mt-2" role="alert">
              <div>
                <h4 class="alert-title">Don't Close This Page</h4>
                <div class="alert-message" style="max-height: 300px; overflow-y: auto;" >${this.message}</div>
              </div>
            </div>
      </div>
    </div>`;
  },
};

export default {
  components: {
    "sokeio::updating-component": updatingComponent,
  },
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
  $updatingComponent: null,
  register() {
    let self = this;
    let productInfo = this.$wire.productInfo;
    self.productId = productInfo.product_id;
    self.productName = productInfo.product_name;
    self.productVersion = productInfo.product_version;
    self.framework = productInfo.framework;
    self.modules = productInfo.modules;
    self.themes = productInfo.themes;
    self.refresh(1);
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
                        <span class="badge bg-red text-bg-red">
                        ${this.productVersion}
                        </span></h3>
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
    let self = this;
    let checkUpdateStatus = function () {
      self.$wire.checkUpdateStatus().then(function (res) {
        if (res) {
          self.$updatingComponent.processUpdate(res.process, res.message);
          checkUpdateStatus();
        } else {
          self.$app.$el.querySelector(".so-modal-close").style.display =
            "block";
          self.$updatingComponent.processUpdate(100, "Update Complete");
        }
      });
    };
    setTimeout(function () {
      console.log("start update");
      self.$wire.startUpdate().then(function (res) {
        self.$updatingComponent.processUpdate(0, "Start Update");
        setTimeout(function () {
          self.$request.get(res).then(function () {
            checkUpdateStatus();
          });
        }, 1000);
      });
    });
  },

  render() {
    return `
      <div class="p-3">
          <h3 class="fw-bold mb-3">System Updater</h3>
          ${this.productRender()}
          ${this.buttonRender()}
         <so:sokeio::updating-component></so:sokeio::updating-component>
      </div>
      `;
  },
};
