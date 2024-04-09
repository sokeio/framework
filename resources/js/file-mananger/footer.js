import { Component } from "../sokeio/component";

export class Footer extends Component {
  state = {
    fileSelectedCount: 0,
    folderSelectedCount: 0,
  };
  init() {
    this.$main.watch("selectFiles", (newValue, oldValue, proValue) => {
      this.fileSelectedCount = this.$main.selectFiles?.length ?? 0;
    });
    this.$main.watch("selectFolders", (newValue, oldValue, proValue) => {
      this.folderSelectedCount = this.$main.selectFolders?.length ?? 0;
    })
  }
  closeApp() {
    this.$main.closeApp();
  }
  selectOk() {
    this.$main.selectOk();
  }
  render() {
    return `
    <div class="footer-wrapper">
      <div class="footer-info">
        <div class="box-info">
          <div class="label-info">File Selected</div>
          <div class="value-info" s-text="fileSelectedCount"></div>
        </div>
        <div class="box-info">
          <div class="label-info">Folder Selected</div>
          <div class="value-info" s-text="folderSelectedCount"></div>
        </div>
      </div>
      <div class="footer-button">
        <button class="btn btn-danger" s-on:click="this.closeApp()">Cancel</button>
        <button class="btn btn-blue" s-on:click="this.selectOk()">OK</button>
      </div>
    </div>
      `;
  }
}
