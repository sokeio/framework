export default {
  state: {
    title: "abc",
    name: "abc",
    current: "",
    isHide: true,
  },
  register() {
    this.$parent.$modalUpload = this;
  },
  cancel() {
    this.isHide = true;
    this.reRender();
  },
  open(name, title, current) {
    this.name = name;
    this.title = title;
    this.current = current;
    this.isHide = false;
    this.reRender();
  },
  render() {
    if (this.isHide) return "<div style='display:none'></div>";
    return `
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 so-text="title">Upload</h3>
                              </div>
                              <div class="so-fm-modal-body">
                                  <input type="file"   style="display:none"/>
                              <div class="so-dropzone">
                              Upload file
                                  </div>
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
