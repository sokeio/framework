import { Component } from "../sokeio/component";

export class CtxMenu extends Component {
  state = {
    item: null,
  };
  closeApp() {
    this.$main.closeApp();
  }
  init() {
    let hideCtxMenu = () => {
      if (this.$el?.style?.display) {
        this.$el.style.display = "none";
      }
    };
    this.onDestroy(() => {
      document.removeEventListener("click", hideCtxMenu);
    });
    document.addEventListener("click", hideCtxMenu);
  }
  setEvent(e, item) {
    e.preventDefault && e.preventDefault();
    e.stopPropagation && e.stopPropagation();
    e.stopImmediatePropagation && e.stopImmediatePropagation();
    this.item = item;
    this.$el.style.display = "block";
    const menuX = e.clientX + 1;
    const menuY = e.clientY + 1;
    this.$el.style.left = menuX + "px";
    this.$el.style.top = menuY + "px";
    this.queryAll(".item-folder", (el) => {
      if (item && item.type == "folder") {
        el.style.display = "block";
      } else {
        el.style.display = "none";
      }
    });
    this.queryAll(".item-file", (el) => {
      if (item && item.type == "file") {
        el.style.display = "block";
      } else {
        el.style.display = "none";
      }
    });
  }
  editImage() {
    this.$main.editImage(this.item);
  }
  downloadFile() {
    this.$main.actionManager("downloadFile", { item: this.item }, (rs) => {});
  }
  rename() {
    let elInputText = this.$main.inputText(
      "Rename",
      this.item.name_without_ext ?? this.item.name,
      (data) => {
        this.$main.actionManager(
          "rename",
          { item: this.item, name: data },
          (rs) => {
            if (rs) {
              elInputText.doClose();
            }
          }
        );
      }
    );
  }
  delete() {
    let elConfirmDelete = this.$main.confirm(
      "Delete",
      "Are you sure you want to delete it?\n" +
        (this.item.name_without_ext ?? this.item.name),
      (data) => {
        this.$main.actionManager(
          "delete",
          { item: this.item, name: data },
          (rs) => {
            if (rs) {
              elConfirmDelete.doClose();
            }
          }
        );
      }
    );
  }
  render() {
    return `
    <ul class="fm-ctxmenu">
        <li title="JS Functions" class="heading"><span>Action</span></li>
        <li title="Rename" class="interactive" s-on:click="this.rename()"><span>Rename</span></li>
        <li title="Delete" class="interactive" s-on:click="this.delete()"><span>Delete</span></li>
        <li title="Download" class="interactive item-file" s-on:click="this.downloadFile()"><span>Download</span></li>
        <li title="Edit Image" class="interactive item-file" s-on:click="this.editImage()"><span>Edit Image</span></li>
    </ul>
      `;
  }
}
