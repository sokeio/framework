import breadcrumbs from "./component/breadcrumbs";
import grid from "./component/grid";
export default {
  components: {
    "media-storage::breadcrumbs": breadcrumbs,
    "media-storage::grid": grid,
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
  register() {
    this.refreshData();
  },
  setResult(result) {
    this.files = result.files;
    this.folders = result.folders;
    this.path = result.path ?? this.path;
  },
  refreshData() {
    this.mediaAction("refresh");
  },
  mediaAction(action = "refresh", data = {}) {
    this.$request
      .post("/api/platform/media-store", {
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
