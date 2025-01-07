export default {
  downloadFile() {
    let self = this;
    this.$app.mediaStorage.mediaAction(
      "downloadFile",
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
                You want to delete file <b>${this.$app.path}</b>
            </div>
            <div class="d-flex justify-content-center mt-2" >
              <button so-on:click="downloadFile()" class="btn btn-danger p-2"><i class="ti ti-download me-1"></i> Download File</button>
            </div>
        </div>
        `;
  },
};
