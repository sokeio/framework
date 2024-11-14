export default {
  state: {
    title: "abc",
    name: "abc",
    current: "",
    isHide: true,
    files: [],
  },
  register() {
    this.$parent.$modalUpload = this;
  },
  cancel() {
    this.isHide = true;
    this.files = [];
    this.reRender();
  },
  ok() {
    // convert this.files To FileList
    this.$parent.fmAction("upload", {}, this.files, (function (progress) {
      if (progress.progress == 100) {
        this.cancel();
      }
    }).bind(this));
    // this.isHide = true;
    // this.reRender();
    // this.files = [];
  },
  ready() {
    setTimeout(() => {
      this.$el
        .querySelector('input[type="file"]')
        ?.addEventListener("change", (e) => {
          for (const element of e.target.files) {
            this.files.push(element);
          }
          this.reRender();
        });
    });
  },
  open(name, title, current) {
    this.name = name;
    this.title = title;
    this.current = current;
    this.files = [];
    this.isHide = false;
    this.reRender();
  },
  chooseFile() {
    this.$el.querySelector('input[type="file"]').click();
  },
  fileSize(size) {
    if (size < 1024) {
      return size + "B";
    } else if (size < 1024 * 1024) {
      return (size / 1024).toFixed(2) + "KB";
    } else if (size < 1024 * 1024 * 1024) {
      return (size / 1024 / 1024).toFixed(2) + "MB";
    }
    return (size / 1024 / 1024 / 1024).toFixed(2) + "GB";
  },
  removeFile(index) {
    this.files.splice(index, 1);
    this.reRender();
  },
  removeAll() {
    this.files = null;
    this.reRender();
  },
  fileListRender() {
    if (!this.files || this.files.length == 0) return "";
    let html = `<div class="so-fm-modal-file-list-title">Files (${this.files.length}) <span so-on:click="removeAll()">Remove all</span></div>`;
    html += `<div class="so-fm-modal-file-list">`;
    console.log(this.files);
    for (let i = 0; i < this.files.length; i++) {
      const file = this.files[i];
      html += `<div class="so-fm-modal-upload-file-item" style="text-align:left">
                  <span class="so-fm-modal-file-name">${file.name}</span>
                  <span class="so-fm-modal-file-size">${this.fileSize(
                    file.size
                  )}</span> 
                  <span class="so-fm-modal-file-remove" so-on:click="removeFile(${i})">x</span>
              </div>`;
    }
    html += `</div>`;
    return html;
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
                              <div class="so-fm-modal-body px-4">
                                <input type="file" style="display:none" multiple/>
                                <div class="so-dropzone" so-on:click="chooseFile()">
                                  <span>Upload file</span>
                                </div>
                                
                                  ${this.fileListRender()}
                                
                              </div>
                              <div class="so-fm-modal-footer pt-1">
                                  <button class="btn btn-danger" so-on:click="cancel()">Cancel</button>
                                  <button class="btn btn-primary" so-on:click="ok()">OK</button>
                              </div>
                          </div>
                      </div>
                  </div>
          `;
  },
};
