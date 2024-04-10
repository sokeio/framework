import { Component } from "../../sokeio/component";

export class File extends Component {
  state = {
    file: undefined,
  };
  init() {
    this.onInit(() => {
      this.file = this.$props.file;
    });
    this.onReady(() => {
      this.$main.watch(
        "selectFiles",
        (newValue, oldValue, proValue) => {
          if (newValue.includes(this.file)) {
            this.$el.classList.add("active");
          } else {
            this.$el.classList.remove("active");
          }
        },
        ($callback) => {
          this.onDestroy(() => {
            $callback();
          });
        }
      );
      if (this.$main.selectFiles.includes(this.file)) {
        this.$el.classList.add("active");
      }
    });
    this.on("mouseenter", (e) => {
      if (e.which !== 1) {
        return;
      }
      this.$main.selectFile(this.file);
    });
    this.on("contextmenu", (e) => {
      this.$main.touchFile(this.file, true);
      this.$main.$contextMenu.setEvent(e, this.file);
    });
  }
  isImage() {
    return this.file && /^image\/[a-z]+$/.test(this.file.mime_type);
  }
  afterRender() {
    if (this.file.thumb && this.isImage()) {
      let item = document.createElement("img");
      item.src = this.file.thumb;
      this.query(".item-body", (el) => {
        el.innerHTML = item.outerHTML;
      });
    }
  }

  touchFile() {
    this.$main.touchFile(this.file);
  }
  render() {
    return `
    <div class="item-box">
      <div class="file-box item-wrapper"  s-on:click="this.touchFile()" >
        <div class="item-body"><i class="ti ti-file" style="font-size: 96px;"></i></div>
        <div class="item-name" s-text="file.name"></div>
      </div>
    </div>
      `;
  }
}
