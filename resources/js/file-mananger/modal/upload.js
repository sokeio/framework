import { ready } from "../../framework/lifecycle";

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
    this.reRender();
  },
  ready() {
    setTimeout(() => {
      this.$el
        .querySelector('input[type="file"]')
        ?.addEventListener("change", (e) => {
          const files = e.target.files;
          for (let i = 0; i < files.length; i++) {
            this.files.push(files[i]);
          }
          this.reRender();
        });
    });
  },
  open(name, title, current) {
    this.name = name;
    this.title = title;
    this.current = current;
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
  fileListRender() {
    let html = "";
    for (let i = 0; i < this.files.length; i++) {
      const file = this.files[i];
      html += `
                        <ul class="so-fm-modal-file" style="text-align:left;list-style-type:none">
                            <li class="so-fm-modal-file-name" style="text-align:left">${file.name} <span class="so-fm-modal-file-size">${this.fileSize(file.size)}</span> <span class="so-fm-modal-file-remove" so-on:click="removeFile(${i})">x</span></li>
                        </ul>
                    `;
    }
    return html;
  },
  render() {
    if (this.isHide) return "<div style='display:none'></div>";
    return `
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog" style="width:500px">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 so-text="title">Upload</h3>
                              </div>
                              <div class="so-fm-modal-body px-4">
                                <input type="file" style="display:none" multiple/>
                                <div class="so-dropzone" so-on:click="chooseFile()">
                                  <span>Upload file</span>
                                </div>
                                <div class="so-fm-modal-file-list">
                                  ${this.fileListRender()}
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
