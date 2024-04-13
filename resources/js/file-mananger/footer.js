import { Component } from "../sokeio/component";

export class Footer extends Component {
  state = {
    fileSelectedCount: 0,
    folderSelectedCount: 0,
  };
  init() {
    this.$main.watch("selectFiles", (newValue, oldValue, proValue) => {
      this.fileSelectedCount = this.$main.selectFiles?.length ?? 0;
      if (this.$main.isCallback()) {
        if (this.fileSelectedCount > 0) {
          this.query(".btn-ok", (el) => {
            el.removeAttribute("disabled");
            el.removeAttribute("style");
          });
        } else {
          this.query(".btn-ok", (el) => {
            el.setAttribute("disabled", true);
            el.removeAttribute("style");
          });
        }
      }
    });
    this.$main.watch("selectFolders", (newValue, oldValue, proValue) => {
      this.folderSelectedCount = this.$main.selectFolders?.length ?? 0;
    });
    this.onReady(() => {
      if (this.$main.isCallback()) {
        this.query(".btn-ok", (el) => {
          el.setAttribute("disabled", true);
          el.removeAttribute("style");
        });
        this.query(".btn-cancel", (el) => {
          el.removeAttribute("disabled");
          el.removeAttribute("style");
        });
      } else {
        this.query(".btn-ok", (el) => {
          el.setAttribute("disabled", true);
          el.setAttribute("style", "pointer-events: none;display: none;");
        });
        this.query(".btn-cancel", (el) => {
          el.setAttribute("disabled", true);
          el.setAttribute("style", "pointer-events: none;display: none;");
        });
      }
    });
  }
  closeApp() {
    this.$main.closeApp();
  }
  selectOk() {
    this.$main.selectOk();
  }
  render() {
    return `
    <div class="fm-footer">
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
        <button class="btn btn-danger btn-cancel" s-on:click="this.closeApp()">Cancel</button>
        <button class="btn btn-blue btn-ok" disabled s-on:click="this.selectOk()">OK</button>
      </div>
    </div>
      `;
  }
}
