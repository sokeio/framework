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
  itemRender(item) {
    console.log(item);
    if (item?.children && item?.children?.length > 0) {
      return `<li class="so-fm-item">${item.title} ${this.treeRender(item.children)}</li>`;
    }
    return `<li class="so-fm-item">${item.title} </li>`;
  },
  treeRender(items) {
    let html = "";
    items.forEach((item) => {
      html += this.itemRender(item);
    });
    return `<ul class="so-fm-tree-list">${html}</ul>`;
  },
  render() {
    return `<div class="so-fm-folder-box>${this.diskRender()} <div class="so-fm-tree">${this.itemRender(this.$parent.folders)}</div></div>`;
  },
};
