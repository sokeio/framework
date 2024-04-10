import { Component } from "../../sokeio/component";

export class InputText extends Component {
  state = {
    title: "",
    text: "",
  };
  init() {
    this.onReady(() => {
      this.title = this.$props.title;
      this.text = this.$props.text;
    });
  }
  closeModal() {
    this.$main.isCreateFolder = false;
  }
  doOk() {
    this.$props.onSave?.(this.text);
  }
  render() {
    return `
    <div>
        <div class="fm-modal-overlay" ></div>
        <div class="fm-modal">
            <div class="fm-content" style="width: 400px;">
                <div class="fm-modal-header">
                <h3 class="fm-modal-title" s-text="title"></h3>
                <button class="btn-close" s-on:click="this.doClose()"></button>
                </div>
                <div class="fm-modal-body">
                <div class="form-label">Folder Name</div>
                <input type="text" name="folderName" class="form-control" s-model="text" placeholder="Folder Name" s-on:enter="this.doOk()">
                </div>
                <div class="fm-modal-footer">
                    <button class="btn btn-danger"  s-on:click="this.doClose()">Cancel</button>
                    <button class="btn btn-blue" s-on:click="this.doOk()">OK</button>
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
