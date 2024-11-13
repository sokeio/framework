export default {
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
      `<div class="col-auto ps-4 fw-bold">Disks</div>` +
      `<div class="col">${html}</div></div>`
    );
  },
  checkItemActive(item) {
    return item.path == this.$parent.path;
  },

  openFolder(path) {
    this.$parent.openFolder(path);
    this.reRender();
  },
  itemRender(item) {
    let html = `<li > <div so-on:click="openFolder('${
      item.path
    }')" class="so-fm-folder-item ${
      this.checkItemActive(item) ? "active" : ""
    }" style="padding-left:${item.level * 7+5}px">${item.name}</div>`;

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
