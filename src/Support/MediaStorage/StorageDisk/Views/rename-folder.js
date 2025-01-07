
export default {
  state: {
    nameFolder: "",
    extension: "",
  },
  register() {
    let pathFile = this.$app.path;
    this.nameFolder = pathFile.split("/").pop();
  },
  renameFolder() {
    let self = this;
    this.$app.mediaStorage.mediaAction(
      "renameFolder",
      {
        path: this.$app.path,
        name: this.nameFolder,
      },
      function (res) {
        self.closeApp();
      }
    );
  },
  render() {
    return `
        <div class="p-3 mt-2" >
            <div>
                You want to rename this folder <b>${this.$app.path}</b>
            </div>
              <div class="mb-2 sokeio-field-input ">
                <div class="input-group">
                  <input so-model="nameFolder" type="text" class="form-control"  placeholder="Folder">
                </div>
              </div>
            <div class="d-flex justify-content-center mt-2" >
              <button so-on:click="renameFolder()" class="btn btn-danger p-2"><i class="ti ti-edit me-1"></i> Rename Folder</button>
            </div>
        </div>
        `;
  },
};
