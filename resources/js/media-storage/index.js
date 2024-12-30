import breadcrumbs from "./component/breadcrumbs";
import grid from "./component/grid";
import loading from "./component/loading";
import contextMenu from "./component/context-menu";
import header from "./component/header";
export default {
  components: {
    "media-storage::breadcrumbs": breadcrumbs,
    "media-storage::grid": grid,
    "media-storage::loading": loading,
    "media-storage::context-menu": contextMenu,
    "media-storage::header": header,
  },
  state: {
    services: {},
    search: "",
    path: "/",
    service: "local",
    files: [],
    folders: [],
    folderSelected: [],
    fileSelected: [],
    menuContext: [],
    views: {},
    toolbar: [],
  },
  $loading: null,
  $contextMenu: null,
  $grid: null,
  $btnChoose: null,

  register() {
    this.fileSelected = [];
    this.folderSelected = [];
    this.path = "/";
    this.search = "";
    this.service = "local";
    this.refreshData();
  },
  searchData(value) {
    this.search = value;
    this.$grid.refresh();
  },
  goBack() {
    let path = this.path.split("/");
    path.pop();
    path = path.join("/");
    if (path == "") path = "/";
    this.openPath(path);
  },
  checkItemActive(path, type = "folder") {
    if (type == "folder") return this.folderSelected.includes(path);
    return this.fileSelected.includes(path);
  },
  selectFile(path) {
    if (this.fileSelected.includes(path)) {
      this.fileSelected = this.fileSelected.filter((item) => item != path);
    } else {
      if (!this.$app.multiple) {
        this.fileSelected = [];
      }
      this.fileSelected.push(path);
    }
    this.$grid.refresh();
    if (this.$btnChoose) {
      this.$btnChoose.disabled = this.fileSelected.length == 0;
    }
  },
  openPath(path) {
    this.path = path;
    this.refreshData();
  },
  setResult(result) {
    this.files = result?.files ?? [];
    this.folders = result?.folders ?? [];
    this.path = result?.path ?? this.path;
    this.menuContext = result?.menuContext ?? [];
    this.views = result?.views ?? {};
    this.toolbar = result?.toolbar ?? [];
  },
  refreshData() {
    this.mediaAction("refresh");
  },
  mediaAction(
    action = "refresh",
    data = {},
    callback = null,
    files = [],
    progress = null
  ) {
    this.$loading?.showLoading();
    let self = this;
    if (files.length > 0) {
      let formData = this.$request.convertJsonToFormData({
        action,
        data,
        type: this.service,
        path: this.path,
      });
      for (let item in files) {
        formData.append("data[files][]", files[item]);
      }
      request = this.$request
        .upload("/platform/media-store", formData, {
          progress: progress,
        })
        .then((res) => {
          res = JSON.parse(res);
          self.setResult(res.result);
          self.services = res.services;
          self.refresh();
          if (callback) {
            callback(res);
          }
        });
      return;
    }
    this.$request
      .post("/platform/media-store", {
        action,
        data,
        type: this.service,
        path: this.path,
      })
      .then((res) => res.json())
      .then((res) => {
        // res = JSON.parse(res);
        self.setResult(res.result);
        self.services = res.services;
        self.refresh();
        if (callback) {
          callback(res);
        }
      });
  },
  chooseOK() {
    let arr = this.fileSelected.map((item) =>
      this.files.find((file) => file.path == item)
    );
    if (!this.$app.multiple) {
      arr = arr.length > 0 ? arr[0] : null;
    }
    this.fnCallbackAndClose(arr);
  },
  buttonChooseRender() {
    this.$btnChoose = null;
    if (!this.checkFnCallback()) return "";
    let html = "";
    html += `<div class="bg-white p-1 d-flex justify-content-end" >
      <button class="btn btn-primary" so-on:click="chooseOK()" so-refs="$btnChoose" disabled><i class="ti ti-check me-1"></i> Choose</button>
    </div>
    `;
    return html;
  },
  render() {
    return ` <div class="so-media-storage-wrapper">
                <so:media-storage::header></so:media-storage::header>
                <so:media-storage::breadcrumbs></so:media-storage::breadcrumbs>
                <div class="so-media-storage-body">
                    <div class="so-media-storage-body-list">
                        <so:media-storage::loading></so:media-storage::loading>
                        <so:media-storage::grid></so:media-storage::grid>
                    </div>
                </div>
                <div class="so-media-storage-footer">
                    <div class="so-media-storage-footer-title">
                        <span>Media Storage</span>
                    </div>
                </div>
                ${this.buttonChooseRender()}
                <so:media-storage::context-menu></so:media-storage::context-menu>
        </div>`;
  },
};
