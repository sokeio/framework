import folderBox from "./folder-box";
import header from "./header";
import footer from "./footer";
import newFolder from "./newFolder";
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
    $modalNewFolder: null,
    $modalUpload: null,
  },
  boot() {
    this.cleanup(function () {});
    this.refreshSelected();
  },

  createFolder() {
    console.log(this.$root);
    this.$root.fnCallback("createFolder");
    this.$modalNewFolder.open("New Folder", "New Folder", this.path);
  },
  uploadFile() {
    this.$modalUpload.open("Upload", "Upload", this.path);
  },
  deleteSelected() {
    alert("delete selected");
  },
  renameSelected() {
    alert("rename selected");
  },
  refreshSelected() {
    this.$request
      .post("/platform/file-manager", { path: this.path })
      .then((res) => {
        console.log(res);
      });
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
