export default {
  state: {
    title: "abc",
    name: "abc",
    current: "",
    path: "",
    isHide: true,
  },
  register() {
    this.$parent.$modalNewFolder = this;
  },
  cancel() {
    this.isHide = true;
    this.refresh();
  },
  open(name, title, current) {
    this.name = name;
    this.title = title;
    this.current = current;
    this.isHide = false;
    this.refresh();
  },
  ok() {
    if (!this.name) {
      this.cancel();
      return;
    }
    this.isHide = true;
    this.refresh();
    this.$parent.fmAction("create-folder", {
      name: this.name,
      current: this.current,
    });
  },
  render() {
    if (this.isHide) return "<div style='display:none'></div>";
    return `
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 ><i class="ti ti-folder-plus fs-2"></i> <span class="fw-bold" so-text="title"></span></h3>
                              </div>
                              <div class="so-fm-modal-body px-2">
                                  <input type="text" so-model="name" placeholder="Enter folder name"  class="form-control">
                              </div>
                              <div class="so-fm-modal-footer pt-2">
                                  <button class="btn btn-danger" so-on:click="cancel()">Cancel</button>
                                  <button class="btn btn-primary" so-on:click="ok()">OK</button>
                              </div>
                          </div>
                      </div>
                  </div>
          `;
  },
};
