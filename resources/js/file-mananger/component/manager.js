import folderBox from "./folder-box";
import header from "./header";
import footer from "./footer";
import newFolder from "./new-folder";
import upload from "./upload";
import gridFile from "./grid-file";
export default {
  components: {
    "so-fm::header": header,
    "so-fm::folder-box": folderBox,
    "so-fm::grid-file": gridFile,
    "so-fm::footer": footer,
    "so-fm::new-folder": newFolder,
    "so-fm::upload": upload,
  },
  state: {
    path: "/",
    files: [],
    folders: [],
    disks: [],
    disk: "public",
    $modalNewFolder: null,
    $modalUpload: null,
  },
  register() {
    this.refreshSelected();
    this.watch("disk", function (value, oldValue) {
      if (value != oldValue) {
        this.path = "/";
        this.refreshSelected();
      }
    });
  },
  boot() {
    this.cleanup(function () {});
  },
  fmAction(action, payload = {}) {
    this.$request
      .post("/platform/file-manager", {
        action,
        payload,
        disk: this.disk,
        path: this.path,
      })
      .then((res) => res.json())
      .then((res) => {
        this.files = res.files ?? this.files;
        this.folders = res.folders ?? this.folders;
        this.disks = res.disks ?? this.disks;
        this.disk = res.disk ?? this.disk;
        this.path = res.path ?? this.path;
        if (this.path == "") this.path = "/";
        this.reRender();
      });
  },

  createFolder() {
    this.$modalNewFolder.open("New Folder", "New Folder", this.path);
  },
  uploadFile() {
    this.$modalUpload.open("Upload", "Upload", this.path);
  },
  deleteSelected() {
    alert("delete selected");
  },
  renameSelected() {
    this.$modalNewFolder.open("New Folder", "New Folder", this.path);
  },
  refreshSelected() {
    this.fmAction("list");
  },
  openFolder(path) {
    this.path = path;
    this.reRender();
    this.fmAction("list");
  },
  render() {
    return ` <div class="so-fm-wrapper">
          [so-fm::header /]
          <div class="so-fm-body">
              <div class="so-fm-folder-box">
              [so-fm::folder-box /]
              </div>
              <div class="so-fm-body-list">
                  [so-fm::grid-file /]
              </div>
          </div>
          [so-fm::footer /]
          [so-fm::new-folder /]
          [so-fm::upload /]
        </div>`;
  },
};
