let ListFileComponent = {
  state: {
    totalSize: 0,
    countFile: 0,
  },
  register() {
    this.$parent.$listFile = this;
  },
  removeFile(index) {
    // remove file by index
    if (!this.$parent.files) this.$parent.files = [];

    let files = this.$parent.files;
    files.splice(index, 1);
    this.$parent.files = files;
    this.refresh();
  },
  fileSize(size) {
    if (size < 1024) {
      return size + "B";
    }
    if (size < 1024 * 1024) {
      return (size / 1024).toFixed(2) + "KB";
    }
    return (size / (1024 * 1024)).toFixed(2) + "MB";
  },
  listFileRender() {
    let files = this.$parent.files;
    if (!files || files.length == 0) return "";
    let html = "";
    this.totalSize = 0;
    this.countFile = files.length;
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      this.totalSize += file.size;
      let size = this.fileSize(file.size);
      html += `
      <li class="list-group-item d-flex justify-content-between align-items-center p-2">
        <span class="badge bg-primary text-bg-primary rounded-pill me-2">${
          i + 1
        }</span>
        <span class="flex-fill">${file.name}</span>
        <span class="badge bg-primary text-bg-primary rounded-pill me-2 ms-2 px-2 flex-none" title="${size}">${size}</span>
        <span class="badge bg-danger text-bg-danger rounded-pill" so-on:click="removeFile(${i})">x</span>
      </li>
      `;
    }
    return html;
  },
  totalRender() {
    if (!this.countFile) return "";
    return ` <div class="d-flex justify-content-between mt-2 px-2">
        <span class="badge bg-primary text-bg-primary rounded-pill">Files:${
          this.countFile
        }</span>
        <span class="badge bg-primary text-bg-primary rounded-pill">Total Size:${this.fileSize(
          this.totalSize
        )}</span>
      </div>`;
  },
  render() {
    return `
    <div>
     <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
        ${this.listFileRender()}
      </div>
     ${this.totalRender()}
    </div>
      `;
  },
};
export default {
  components: { "local:list-file": ListFileComponent },
  state: {
    files: [],
  },
  $listFile: null,
  uploadFile() {
    this.$app.mediaStorage.mediaAction("uploadFile", {
      files: this.files,
    });
  },
  changeFile(e) {
    [...e.target.files].forEach((file) => {
      this.files.push(file);
    });
    this.$listFile.refresh();
    e.target.value = null;
  },
  render() {
    return `
      <div class="p-3" data-modal-size="sm">
          <div class="mb-2 sokeio-field-input">
            <label class="form-label">File</label>
            <input type="file" class="form-control" multiple so-on:change="changeFile($event)" >
          </div>
          <so:local:list-file></so:local:list-file>
          <div class="d-flex justify-content-center mt-2" >
            <button so-on:click="uploadFile()" class="btn btn-primary p-2">Upload File</button>
          </div>
      </div>
      `;
  },
};
