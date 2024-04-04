import { Component } from "../../sokeio/component";

export class File extends Component {
  state = {
    fileId: 0,
    fileName: "",
  };
  init() {
    this.onReady(() => {
      this.fileId = this.$props.file.fileId;
      this.fileName = this.$props.file.fileName;

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
      e.preventDefault();
      this.$main.selectFile(this.fileId);

      console.log({ fn: "mouseenter", e, fileId: this.fileId });
    });
    this.on(
      ["selectstart", "selectend"],
      (e) => {
        e.preventDefault();
        e.target.selectend = e.target.selectstart=false;
      },
      ".file-box"
    );
  }

  touchFile() {
    console.log({ fn: "touchFile", fileId: this.fileId });
    this.$main.touchFile(this.fileId);
  }
  render() {
    return `
    <div class="item-box">
        <div class="file-box" s-on:click="this.touchFile()" s-text="fileName">
        File
        </div>
    </div>
      `;
  }
}
