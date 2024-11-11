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
    this.reRender();
  },
  open(name, title, current, path) {
    this.name = name;
    this.title = title;
    this.current = current;
    this.path = path;
    this.isHide = false;
    this.reRender();
  },
  ok() {
    this.isHide = true;
    this.reRender();
    this.$parent.changeFolder({
      name: this.name,
      current: this.current,
      path: this.path,
    });
  },
  render() {
    if (this.isHide) return "<div style='display:none'></div>";
    return `
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 so-text="title">New Folder</h3>
                              </div>
                              <div class="so-fm-modal-body">
                                  <input type="text" so-model="name"  class="form-control">
                              </div>
                              <div class="so-fm-modal-footer pt-1">
                                  <button class="btn btn-danger" so-on:click="cancel()">Cancel</button>
                                  <button class="btn btn-primary">OK</button>
                              </div>
                          </div>
                      </div>
                  </div>
          `;
  },
};
