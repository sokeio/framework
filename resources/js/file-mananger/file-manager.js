import folderBox from "./component/folder-box";
import header from "./component/header";
import footer from "./component/footer";
import newFolder from "./modal/new-folder";
import upload from "./modal/upload";
import gridFile from "./component/grid-file";
import loading from "./component/loading";
export default {
  components: {
    "so-fm::header": header,
    "so-fm::folder-box": folderBox,
    "so-fm::grid-file": gridFile,
    "so-fm::footer": footer,
    "so-fm::new-folder": newFolder,
    "so-fm::upload": upload,
    "so-fm::loading": loading,
  },
  state: {
    path: "/",
    selected: [],
    files: [],
    folders: [],
    fileCount: 0,
    disks: [],
    disk: "public",
    boxFolderScrollTop: 0,
    $modalNewFolder: null,
    $modalUpload: null,
    $loading: null,
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
  chooseFile(path, multiple = false) {
    if (!multiple) {
      this.selected = [];
    }
    this.selected.push(path);
    this.$el.querySelector(".so-fm-footer-action button").disabled = false;
  },
  checkItemActive(path) {
    return this.selected.includes(path);
  },
  fmAction(action, payload = {}, files = [], progress = null) {
    this.$loading?.showLoading();
    let request = null;
    if (files.length > 0) {
      let formData = this.$request.convertJsonToFormData({
        action,
        disk: this.disk,
        path: this.path,
      });
      for (let item in files) {
        formData.append("files[]", files[item]);

        console.log("files[]", files[item]);
      }
      request = this.$request
        .upload("/platform/file-manager", formData, {
          progress: progress,
        })
        .then((res) => {
          return JSON.parse(res);
        });
    } else {
      request = this.$request
        .post("/platform/file-manager", {
          action,
          payload,
          disk: this.disk,
          path: this.path,
        })
        .then((res) => res.json());
    }

    request.then((res) => {
      this.files = res.files ?? this.files;
      this.folders = res.folders ?? this.folders;
      this.disks = res.disks ?? this.disks;
      this.disk = res.disk ?? this.disk;
      this.path = res.path ?? this.path;
      this.fileCount = this.files?.length ?? 0;
      if (this.path == "") this.path = "/";
      this.$loading?.hideLoading();
      this.reRender();
    });
  },

  createFolder() {
    this.$modalNewFolder.open("", "New Folder", "");
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
    this.selected = [];
    this.reRender();
    this.fmAction("list");
  },
  footerRender() {
    if (!this.$root.fnCallback) {
      return "";
    }
    let html = `<div class="so-fm-footer-action">`;
    html += `<button so-on:click="chooseFileAction()" ${
      this.selected.length == 0 ? "disabled" : ""
    } class="btn btn-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg> Choose File</button>`;
    html += `</div>`;
    return html;
  },
  chooseFileAction() {
    if (this.selected.length == 0) return;
    let files = [];
    this.selected.forEach((item) => {
      this.files.filter((i) => i.path == item).forEach((i) => files.push(i));
    });
    this.$root.fnCallback(files, this.path);
    this.$root.delete();
  },
  render() {
    return ` <div class="so-fm-wrapper">
          [so-fm::header /]
          <div class="so-fm-body">
              <div class="so-fm-folder-box">
              [so-fm::folder-box /]
              </div>
              <div class="so-fm-body-list">
                  [so-fm::loading /]
                  [so-fm::grid-file /]
              </div>
          </div>
          [so-fm::footer /]
          ${this.footerRender()}
          [so-fm::new-folder /]
          [so-fm::upload /]
        </div>`;
  },
};
