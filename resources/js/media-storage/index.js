import breadcrumbs from "./component/breadcrumbs";
import grid from "./component/grid";
import loading from "./component/loading";
export default {
  components: {
    "media-storage::breadcrumbs": breadcrumbs,
    "media-storage::grid": grid,
    "media-storage::loading": loading,
  },
  state: {
    services: {},
    path: "/",
    service: "local",
    files: [],
    folders: [],
    folderSelected: [],
    fileSelected: [],
  },
  $loading: null,
  register() {
    this.refreshData();
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
  },
  refreshData() {
    this.mediaAction("refresh");
  },
  mediaAction(action = "refresh", data = {}) {
    this.$loading?.showLoading();
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
      });
  },
  render() {
    return ` <div class="so-media-storage-wrapper">
                <div class="so-media-storage-header">
                </div>
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
        </div>`;
  },
};
