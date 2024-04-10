import { Component } from "../../sokeio/component";

export class Confirm extends Component {
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
                    <div class="form-label" s-text="text"></div>
                </div>
                <div class="fm-modal-footer">
                    <button class="btn btn-danger"  s-on:click="this.doClose()">No</button>
                    <button class="btn btn-blue" s-on:click="this.doOk()">Yes</button>
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
