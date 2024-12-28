export default {
  state: {
    folderName: "",
  },
  createFolder() {
    this.$app.mediaStorage.mediaAction("createFolder", {
      name: this.folderName,
    });
  },
  render() {
    return `
      <div class="p-3" data-modal-size="sm">
          <div class="mb-2 sokeio-field-input">
            <label class="form-label">Folder Name</label>
            <input so-model="folderName" type="text" class="form-control "  placeholder="Folder Name">
          </div>
          <div class="d-flex justify-content-center mt-2" >
            <button so-on:click="createFolder()" class="btn btn-primary p-2">Create Folder</button>
          </div>
      </div>
      `;
  },
};
