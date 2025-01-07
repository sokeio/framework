export default {
  deleteFolder() {
    let self = this;
    this.$app.mediaStorage.mediaAction(
      "deleteFolder",
      {
        path: this.$app.path,
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
                You want to delete folder <b>${this.$app.path}</b>
            </div>
            <div class="d-flex justify-content-center mt-2" >
              <button so-on:click="deleteFolder()" class="btn btn-danger p-2"><i class="ti ti-trash me-1"></i> Delete Folder</button>
            </div>
        </div>
        `;
  },
};
