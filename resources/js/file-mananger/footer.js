import { Component } from "../sokeio/component";

export class Footer extends Component {
  state = {
    fileSelectedCount: 0,
  };
  init() {
    this.$main.watch("selectFiles", (newValue, oldValue, proValue) => {
      this.fileSelectedCount = this.$main.selectFiles.length;
    });
  }
  render() {
    return `
    <div class="footer-wrapper">
      <div class="footer-info">
        <div class="box-info">
          <div class="label-info">File Selected</div>
          <div class="value-info" s-text="fileSelectedCount"></div>
        </div>
      </div>
      <div class="footer-button">
        <button class="btn btn-danger">Cancel</button>
        <button class="btn btn-blue">OK</button>
      </div>
    </div>
      `;
  }
}
