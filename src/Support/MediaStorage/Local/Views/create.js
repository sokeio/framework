export default {
  state: {
    folderName: "",
  },
  createFolder() {
    let self=this;
    this.$app.mediaStorage.mediaAction("createFolder", {
      name: this.folderName,
    },
    function (res) {
      self.closeApp();
    });
  },
  render() {
    return `
      <div class="p-3" >
          <div class="mb-2 sokeio-field-input">
            <label class="form-label">Folder Name</label>
            <input so-model="folderName" type="text" class="form-control "  placeholder="Folder Name">
          </div>
          <div class="d-flex justify-content-center mt-2" >
            <button so-on:click="createFolder()" class="btn btn-primary p-2"><i class="ti ti-folder-plus me-1"></i> Create Folder</button>
          </div>
      </div>
      `;
  },
};
