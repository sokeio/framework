import folderBox from "./component/folder-box";
import header from "./component/header";
import footer from "./component/footer";
import newFolder from "./modal/new-folder";
import upload from "./modal/upload";
import gridFile from "./component/grid-file";
import loading from "./component/loading";
import contextMenu from "./component/context-menu";
export default {
  components: {
    "so-fm::header": header,
    "so-fm::folder-box": folderBox,
    "so-fm::grid-file": gridFile,
    "so-fm::footer": footer,
    "so-fm::new-folder": newFolder,
    "so-fm::upload": upload,
    "so-fm::loading": loading,
    "so-fm::context-menu": contextMenu,
  },
  $modalNewFolder: null,
  $modalUpload: null,
  $grid: null,
  $folderBox: null,
  $loading: null,
  $contextMenu: null,
  btnChooseFileAction: null,
  state: {
    path: "/",
    selected: [],
    selectedCount: 0,
    files: [],
    folders: [],
    fileCount: 0,
    disks: [],
    disk: "public",
    boxFolderScrollTop: 0,
  },
  toolbars: [
    {
      title: "New Folder",
      icon: "ti ti-folder-plus",
      action: function () {
        this.createFolder();
      },
    },
    {
      title: "Upload File",
      icon: "ti ti-upload",
      action: function () {
        this.uploadFile();
      },
    },
    {
      title: "Refresh",
      icon: "ti ti-refresh",
      action: function () {
        this.refreshSelected();
      },
    },
  ],
  contextMenus: [
    {
      title: "Refresh",
      icon: "ti ti-refresh",
      type: ["folder", "file"],
      action: function () {
        this.refreshSelected();
      },
    },
    {
      title: "Rename",
      icon: "ti ti-edit",
      type: ["folder", "file"],
      action: function () {
        this.createFolder();
      },
    },
    {
      title: "Remove",
      icon: "ti ti-trash",
      type: ["folder", "file"],
      action: function () {
        this.uploadFile();
      },
    },
    {
      title: "Download",
      icon: "ti ti-download",
      type: ["file"],
      action: function () {
        this.refreshSelected();
      },
    },
    {
      title: "Share",
      icon: "ti ti-share",
      type: ["file"],
      action: function () {
        alert("share");
      },
    },
  ],
  showMenuContext($event, $path, $type) {
    this.selected = [];
    this.$contextMenu?.open($event, $path, $type);
  },
  register() {
    this.path = "/";
    this.disk = "public";
    this.selected = [];
    this.refreshSelected();
    this.watch("disk", function (value, oldValue) {
      if (value != oldValue) {
        this.path = "/";
        this.refreshSelected();
      }
    });
  },
  boot() {
    this.onDestroy(function () {});
    this.watch("selected", function (value, oldValue) {
      if (value != oldValue) {
        this.selectedCount = this.selected.length;
      }
    });
    this.watch("selectedCount", function (value, oldValue) {
      if (value != oldValue) {
        this.btnChooseFileAction.disabled = value == 0;
      }
    });
  },
  chooseFile(path) {
    if (!this.$app.multiple) {
      this.selected = [];
    }
    let selected = this.selected;
    if (selected.includes(path)) {
      selected = selected.filter((item) => item != path);
    } else {
      selected.push(path);
    }
    this.selected = selected;
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
      this.$grid?.refresh();
      this.$folderBox?.refresh();
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
    this.$grid?.refresh();
    this.fmAction("list");
  },
  footerRender() {
    if (!this.$app?.fnCallback) {
      return "";
    }
    let html = `<div class="so-fm-footer-action">`;
    html += `<button so-refs="btnChooseFileAction" so-on:click="chooseFileAction()" ${
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
    this.$app.fnCallback(this.$app.multiple ? files : files[0], this.path);
    this.closeApp();
  },
  render() {
    return ` <div class="so-fm-wrapper">
          <so:so-fm::header></so:so-fm::header>
          <div class="so-fm-body">
              <div class="so-fm-folder-box">
               <so:so-fm::folder-box></so:so-fm::folder-box>
              </div>
              <div class="so-fm-body-list">
                   <so:so-fm::loading></so:so-fm::loading>
                   <so:so-fm::grid-file></so:so-fm::grid-file>
              </div>
          </div>
           <so:so-fm::footer></so:so-fm::footer>
          ${this.footerRender()}
           <so:so-fm::new-folder></so:so-fm::new-folder>
           <so:so-fm::upload></so:so-fm::upload>
           <so:so-fm::context-menu></so:so-fm::context-menu>
        </div>`;
  },
};
