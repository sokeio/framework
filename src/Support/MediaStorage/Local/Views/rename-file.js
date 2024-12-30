
export default {
  state: {
    nameFile: "",
    extension: "",
  },
  register() {
    let pathFile = this.$app.path;
    this.nameFile = pathFile.split("/").pop();
    this.extension = this.nameFile.split(".").pop();
    this.nameFile = this.nameFile.split(".").shift();
  },
  renameFile() {
    let self = this;
    this.$app.mediaStorage.mediaAction(
      "renameFile",
      {
        path: this.$app.path,
        name: this.nameFile + "." + this.extension,
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
                You want to rename this file <b>${this.$app.path}</b>
            </div>
              <div class="mb-2 sokeio-field-input ">
                <div class="input-group">
                  <input so-model="nameFile" type="text" class="form-control"  placeholder="File">
                  <span class="input-group-text">.${this.extension}</span>
                </div>
              </div>
            <div class="d-flex justify-content-center mt-2" >
              <button so-on:click="renameFile()" class="btn btn-danger p-2"><i class="ti ti-edit me-1"></i> Rename File</button>
            </div>
        </div>
        `;
  },
};
