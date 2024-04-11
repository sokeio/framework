import { Component } from "../../sokeio/component";

export class Folder extends Component {
  state = {
    folder: undefined,
  };
  init() {
    this.onInit(() => {
      this.folder = this.$props.folder;
    });
    this.onReady(() => {
      this.$main.watch(
        "selectFolders",
        (newValue, oldValue, proValue) => {
          if (newValue.includes(this.folder)) {
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
      if (this.$main.selectFolders.includes(this.folder)) {
        this.$el.classList.add("active");
      }
    });
    this.on("mouseenter", (e) => {
      if (e.which !== 1) {
        return;
      }
      this.$main.selectFolder(this.folder);
    });
    this.on("dblclick", (e) => {
      this.$main.changePath(this.folder.path);
    });
    this.on('contextmenu', (e) => {
      this.$main.touchFolder(this.folder,true);
      this.$main.$contextMenu.setEvent(e, this.folder);
    })
  }

  touchFolder() {
    this.$main.touchFolder(this.folder);
  }
  render() {
    return `
    <li class="item-box">
      <div class="folder-box item-wrapper"  s-on:click="this.touchFolder();" >
        <div class="item-body"><i class="ti ti-folder" style="font-size: 96px;"></i></div>
        <div class="item-name" s-text="folder.name"></div>
      </div>
    </li>
      `;
  }
}
