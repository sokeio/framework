export default {
  components: {},
  state: {},
  register() {
    this.$parent.$grid = this;
  },
  openFolder(path) {
    this.$parent.path = path;
    this.$parent.refreshData();
  },
  showContextMenu(e, path, type) {
    this.$parent.$contextMenu.open(e, path, type);
    e.preventDefault();
  },
  folderRender() {
    let html = "";
    let search = this.$parent.search;
    this.$parent.folders.forEach((item) => {
      if(search && !item.name.toLowerCase().includes(search.toLowerCase())) return
      html += `<div class="so-media-storage-item" so-on:click="openFolder('${item.path}')" so-on:contextmenu='showContextMenu($event,"${item.path}","folder")'>
                        <div class="so-media-storage-item-icon">
                            <i class="ti ti-folder"></i>
                        </div>
                        <div class="so-media-storage-item-name">
                        ${item.name}
                        </div>
                    </div>`;
    });
    return html;
  },
  fileRender() {
    let html = "";
    let search = this.$parent.search;
    this.$parent.files.forEach((item) => {
      if(search && !item.name.toLowerCase().includes(search.toLowerCase())) return
      html += `<div class="so-media-storage-item so-media-storage-item-file" so-on:contextmenu='showContextMenu($event,"${item.path}","file")'>
                        <div class="so-media-storage-item-icon">
                            <i class="ti ti-file"></i>
                        </div>
                        <div class="so-media-storage-item-name">
                           ${item.name}
                        </div>
                    </div>`;
    });
    return html;
  },
  itemBackRender() {
    if (this.$parent.path == "/" || this.$parent.path == "") return "";
    return `<div class="so-media-storage-item " so-on:click="$parent.goBack()">
                        <div class="so-media-storage-item-icon">
                            <i class="ti ti-arrow-back-up"></i>
                        </div>
                    </div>`;
  },
  render() {
    return ` <div class="so-media-storage-grid">
                ${this.itemBackRender()}
                ${this.folderRender()}
                ${this.fileRender()}
            </div>`;
  },
};
