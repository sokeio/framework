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

  register() {
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
          this.setResult(res.result);
          this.services = res.services;
          this.refresh();
          if (callback) {
            callback(res);
          }
          this.$loading?.hideLoading();
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
        this.setResult(res.result);
        this.services = res.services;
        this.refresh();
        if (callback) {
          callback(res);
        }
        this.$loading?.hideLoading();
      });
  },
  render() {
    return ` <div class="so-media-storage-wrapper">
                <so:media-storage::header></so:media-storage::header>
                <div class="so-media-storage-body">
                    <so:media-storage::breadcrumbs></so:media-storage::breadcrumbs>
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
                <so:media-storage::context-menu></so:media-storage::context-menu>
        </div>`;
  },
};
