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
    this.totalSize = 0;
    this.countFile = files.length;
    if (!files || files.length == 0) return "";
    let html = "";
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      this.totalSize += file.size;
      let size = this.fileSize(file.size);
      html += `
      <li class="list-group-item d-flex justify-content-between align-items-center p-2">
        <span class="badge bg-primary text-bg-primary rounded-pill me-2">${
          i + 1
        }</span>
        <span class="flex-grow-1">${file.name}</span>
        <span class="badge bg-primary text-bg-primary rounded-pill me-2 ms-2 px-2" title="${size}">${size}</span>
        <span class="badge bg-danger text-bg-danger rounded-pill" so-on:click="removeFile(${i})">x</span>
      </li>
      `;
    }
    return html;
  },
  loadingRender() {
    if (!this.$parent.loading) return "";
    return `
    <div class="d-flex justify-content-center align-items-center">
        <span class="spinner spinner-border text-blue" role="status"></span>
    </div>
    `;
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
     ${this.loadingRender()}
    </div>
      `;
  },
};

export default {
  components: { "local:list-file": ListFileComponent },
  state: {
    files: [],
    loading: false
  },
  $listFile: null,
  uploadFile() {
    let self = this;
    self.loading = true;
    self.$listFile.refresh();
    this.$app.mediaStorage.mediaAction(
      "uploadFile",
      {},
      function (res) {
        self.closeApp();
      },
      this.files,
      function (progress) {
        console.log(progress);
      }
    );
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
            <button so-on:click="uploadFile()" class="btn btn-primary p-2"><i class="ti ti-upload me-1"></i> Upload File</button>
          </div>
      </div>
      `;
  },
};
