export default {
  components: {},
  state: {},
  folderRender() {
    let html = "";
    this.$parent.folders.forEach((item) => {
      html += `<div class="so-media-storage-item ">
                        <div class="so-media-storage-folder-icon">
                            <i class="fa fa-folder"></i>
                        </div>
                        <div class="so-media-storage-folder-name">
                            <a href="#">${item.name}</a>
                        </div>
                    </div>`;
    });
    return html;
  },
  fileRender() {
    let html = "";
    this.$parent.files.forEach((item) => {
      html += `<div class="so-media-storage-item ">
                        <div class="so-media-storage-file-icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <div class="so-media-storage-file-name">
                            <a href="#">${item.name}</a>
                        </div>
                    </div>`;
    });
    return html;
  },
  itemBackRender() {
    if (this.$parent.path == "/" || this.$parent.path == "") return "";
    return `<div class="so-media-storage-item ">
                        <div class="so-media-storage-folder-icon">
                            <i class="fa fa-folder"></i>
                        </div>
                        <div class="so-media-storage-folder-name">
                            <a href="#">..</a>
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
