import { Component } from "../../sokeio/component";

export class UploadFile extends Component {
  state = {
    fileSelectedCount: 0,
    name: "",
  };
  init() {
    this.onReady(() => {
      this.$main.watch(
        "isUploadFile",
        (newValue, oldValue, proValue) => {
          this.name = "";
          if (newValue) {
            this.removeAttribute("style");
            this.query('input[name="folderName"]', function (el) {
              el.focus();
            });
          } else {
            this.setAttribute("style", "display: none;");
          }
        },
        (destroy) => {
          this.onDestroy(() => {
            destroy();
          });
        }
      );
      this.query(".fm-upload-file", function (el) {
        el.addEventListener("click", function () {
          el.querySelector('input[type="file"]').click();
        });
      });
    });
  }
  closeModal() {
    this.$main.isCreateFolder = false;
  }
  doCreateFolder() {
    if (!this.name) {
      alertt("Folder name is required");
      return;
    }
    this.$main.actionManager("createFolder", { name: this.name }, (rs) => {
      if (rs) {
        this.closeModal();
      }
    });
  }
  render() {
    return `
    <div style="display: none;">
        <div class="fm-modal-overlay" s-on:click="this.closeModal()"></div>
        <div class="fm-modal">
            <div class="fm-content"  style="max-width: 700px;">
                <div class="fm-modal-header">
                    <h3 class="fm-modal-title">Upload File</h3>
                    <button class="btn-close" s-on:click="this.closeModal()"></button>
                </div>
                <div class="fm-modal-body">
                    <div class="fm-upload-file">
                        Upload File
                        <input type="file" style="display: none;" name="file" />
                    </div>
                </div>
                <div class="fm-modal-footer">
                    <button class="btn btn-danger"  s-on:click="this.closeModal()">Cancel</button>
                    <button class="btn btn-blue" s-on:click="this.doCreateFolder()">OK</button>
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
