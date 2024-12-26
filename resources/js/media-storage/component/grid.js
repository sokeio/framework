export default {
  components: {},
  state: {},
  openFolder(path) {
    this.$parent.path = path;
    this.$parent.refreshData();
  },
  folderRender() {
    let html = "";
    this.$parent.folders.forEach((item) => {
      html += `<div class="so-media-storage-item" so-on:click="openFolder('${item.path}')" so-on:contextmenu='showContextMenu($event,"${item.path}")'>
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
    this.$parent.files.forEach((item) => {
      html += `<div class="so-media-storage-item so-media-storage-item-file">
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
