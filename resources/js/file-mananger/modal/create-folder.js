import { Component } from "../../sokeio/component";

export class CreateFolder extends Component {
  state = {
    fileSelectedCount: 0,
    name: "",
  };
  init() {
    this.onReady(() => {
      this.$main.watch(
        "isCreateFolder",
        (newValue, oldValue, proValue) => {
          this.name = "";
          if (newValue) {
            this.removeAttribute("style");
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
    <div class="fm-modal" style="display: none;">
     <div class="fm-content" style="width: 400px;">
        <div class="fm-modal-header">
           <h3 class="fm-modal-title">Create Folder</h3>
           <button class="btn-close" s-on:click="this.closeModal()"></button>
        </div>
        <div class="fm-modal-body">
           <div class="form-label">Folder Name</div>
           <input type="text" class="form-control" s-model="name" placeholder="Folder Name" s-on:enter="this.doCreateFolder()">
        </div>
        <div class="fm-modal-footer">
            <button class="btn btn-danger"  s-on:click="this.closeModal()">Cancel</button>
            <button class="btn btn-blue" s-on:click="this.doCreateFolder()">OK</button>
        </div>
     </div>
    </div>
      `;
  }
}
