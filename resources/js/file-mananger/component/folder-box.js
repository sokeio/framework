export default {
  register() {
    this.$parent.$folderBox = this;
  },
  diskRender() {
    let html = ` <select so-model="$parent.disk" class="form-select mt-1 p-2">`;

    Object.keys(this.$parent.disks).forEach((key) => {
      html += `<option value="${key}" ${
        this.$parent.disk == key ? "selected" : ""
      }>${key}</option>`;
    });
    html += `</select>`;
    return (
      `<div class="row justify-content-center align-items-center">` +
      `<div class="col-auto ps-4 fw-bold">Disk:</div>` +
      `<div class="col">${html}</div></div>`
    );
  },
  checkItemActive(item) {
    return item.path == this.$parent.path;
  },
  ready() {
    setTimeout(() => {
      this.$el.querySelector(".so-fm-folder-wrapper").scrollTop =
        this.$parent.boxFolderScrollTop;
    });
  },
  openFolder(path) {
    this.$parent.boxFolderScrollTop = this.$el.querySelector(
      ".so-fm-folder-wrapper"
    ).scrollTop;
    this.$parent.openFolder(path);
    this.refresh();
  },
  showContextMenu(e, path) {
    this.openFolder(path);
    this.$parent.$contextMenu.open(e, this, "folder");
    e.preventDefault();
  },
  itemRender(item) {
    let html = `<li > <div so-on:click="openFolder('${
      item.path
    }')" class="so-fm-folder-item ${
      this.checkItemActive(item) ? "active" : ""
    }" style="padding-left:${
      item.level * 7 + 5
    }px" so-on:contextmenu='showContextMenu($event,"${item.path}")'>${
      item.name
    }</div>`;

    if (item?.children && item?.children?.length > 0) {
      html += this.treeRender(item.children);
    }
    return `${html}</li>`;
  },
  treeRender(items) {
    let html = "";
    if (Array.isArray(items)) {
      items.forEach((item) => {
        html += this.itemRender(item);
      });
    } else {
      html += this.itemRender(items);
    }

    return `<ul class="so-fm-folder-list">${html}</ul>`;
  },

  render() {
    return `<div class="so-fm-folder-box-wrapper">${this.diskRender()} <div class="so-fm-folder-wrapper"> ${this.treeRender(
      this.$parent.folders
    )}
    </div></div>`;
  },
};
