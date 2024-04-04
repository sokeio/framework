import { Component } from "../../sokeio/component";

export class File extends Component {
  state = {
    fileId: 0,
    fileName: "",
  };
  init() {
    this.onInit(() => {
      this.fileId = this.$props.file.fileId;
      this.fileName = this.$props.file.fileName;
    });
    this.onReady(() => {
      this.$main.watch(
        "selectFiles",
        (newValue, oldValue, proValue) => {
          if (this.$main.selectFiles.includes(this.fileId)) {
            this.$el.classList.add("item-active");
          } else {
            this.$el.classList.remove("item-active");
          }
        },
        ($callback) => {
          this.onDestroy(() => {
            $callback();
          });
        }
      );
      if (this.$main.selectFiles.includes(this.fileId)) {
        this.$el.classList.add("item-active");
      }
    });
    this.on("mouseenter", (e) => {
      if (e.which !== 1) {
        return;
      }
      this.$main.selectFile(this.fileId);
    });
  }

  touchFile() {
    this.$main.touchFile(this.fileId);
  }
  render() {
    return `
    <div class="item-box">
      <div class="file-box item-wrapper"  s-on:click="this.touchFile()" >
        <div class="item-body"></div>
        <div class="item-name" s-text="fileName"></div>
      </div>
    </div>
      `;
  }
}
