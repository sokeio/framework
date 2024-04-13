import { Component } from "../../sokeio/component";

export class UploadFile extends Component {
  state = {
    fileSelectedCount: 0,
    name: "",
  };
  init() {
    this.onReady(() => {
      this.query(".fm-upload-file", function (el) {
        el.addEventListener("click", function () {
          el.querySelector('input[type="file"]').click();
        });
      });
      this.query('input[type="file"]', function (el) {
        el.addEventListener("change", (event) => {
          this.$props.onSave([...event.target.files]);
        });
      });
    });
  }
  closeModal() {
    this.destroy();
  }

  render() {
    return `
    <div>
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
                        <input type="file" multiple style="display: none;" name="file" />
                    </div>
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
