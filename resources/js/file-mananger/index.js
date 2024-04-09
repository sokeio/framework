import { Application } from "../sokeio/application";
import { Body } from "./body";
import { File } from "./component/file";
import { Folder } from "./component/folder";
import { ItemBack } from "./component/item-back";
import { Footer } from "./footer";
import { Header } from "./header";
import { CreateFolder } from "./modal/create-folder";
import { UploadFile } from "./modal/upload-file";
import { PropertyInfo } from "./property";
import { Toolbar } from "./toolbar";

export class FileManager extends Application {
  components = {
    "fm:Header": Header,
    "fm:Toolbar": Toolbar,
    "fm:Body": Body,
    "fm:Footer": Footer,
    "fm:File": File,
    "fm:Folder": Folder,
    "fm:ItemInfo": PropertyInfo,
    "fm:CreateFolder": CreateFolder,
    "fm:UploadFile": UploadFile,
    "fm:ItemBack": ItemBack,
  };
  state = {
    searchText: "",
    directory: "",
    disk: "public",
    path: "/",
    name: "",
    files: [],
    folders: [],
    currentFolder: null,
    currentFile: null,
    selectFiles: [],
    selectFolders: [],
    isCreateFolder: false,
    isUploadFile: false,
  };
  $axios;
  $callbackEvent;
  cast = {
    // demo: (v) => parseInt(v),
  };
  selectFile($file) {
    if (!this.selectFiles.includes($file)) {
      this.selectFiles = [...this.selectFiles, $file];
    }
    this.currentFolder = null;
    this.currentFile = $file;
  }
  touchFile($file) {
    this.selectFolders = [];
    this.currentFolder = null;
    this.currentFile = $file;
    if (this.selectFiles.includes($file)) {
      this.selectFiles = [];
    } else {
      this.selectFiles = [$file];
    }
  }
  selectFolder($folder) {
    if (!this.selectFolders.includes($folder)) {
      this.selectFolders = [...this.selectFolders, $folder];
    }
    this.currentFile = null;
    this.currentFolder = $folder;
  }
  touchFolder($folder) {
    this.selectFiles = [];
    this.currentFile = null;
    this.currentFolder = $folder;
    if (this.selectFolders.includes($folder)) {
      this.selectFolders = [];
    } else {
      this.selectFolders = [$folder];
    }
  }
  changePath($path) {
    this.actionManager("changePath", { path: $path });
  }
  backFolder() {
    if (this.path !== "/") {
      let path = this.path.replace(/\/[^\/]+$/, "");
      if (path === this.path) {
        path = "/";
      }
      this.changePath(path);
    }
  }
  closeApp() {
    this.destroy();
  }
  onCallback($callback) {
    this.$callbackEvent = $callback;
    return this;
  }
  selectOk() {
    if (this.$callbackEvent) {
      this.$callbackEvent({
        files: this.selectFiles,
        folders: this.selectFolders,
      });
    }
    this.closeApp();
  }
  init() {
    this.onReady(() => {
      if (window.SokeioManager) {
        this.$axios = window.SokeioManager.getAxios();
        if (this.$axios) {
          this.refeshData();
        }
      }
    });
    window.addEventListener("sokeio::ready", (e) => {
      this.$axios = window.SokeioManager.getAxios();
      this.refeshData();
    });
  }
  refeshData() {
    this.actionManager("load");
  }
  setDataFileManager($response) {
    this.folders = $response?.folders ?? [];
    this.files = $response?.files ?? [];
    this.disk = $response?.disk ?? "local";
    this.path = $response?.path ?? "/";
    this.name = $response?.name ?? "";
  }
  getDataFileManager() {
    return {
      folders: this.folders,
      files: this.files,
      disk: this.disk,
      path: this.path,
      name: this.name,
    };
  }
  UploadFile(files, $callback = undefined, onUploadProgress = undefined) {
    let formData = new FormData();
    if (!Array.isArray(files)) {
      files = [files];
    }
    for (let file of files) {
      formData.append("files[]", file);
    }
    formData.append("disk", this.disk);
    formData.append("path", this.path);

    this.$axios
      .post("/file-manager/upload", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
        onUploadProgress,
      })
      .then((response) => {
        if (response.data.status == "error") {
          alert(response.data.message);
          if ($callback) {
            $callback(false, { message: response.data.message });
          }
        } else {
          this.setDataFileManager(response.data);
          if ($callback) {
            $callback(true, {});
          }
        }
      })
      .catch((error) => {
        this.setDataFileManager({});
        if ($callback) {
          $callback(false, error);
        }
      });
  }
  actionManager($action = "load", $data = {}, $callback = undefined) {
    this.$axios
      .post("/file-manager", {
        action: $action,
        data: {
          disk: this.disk,
          path: this.path,
          ...$data,
        },
      })
      .then((response) => {
        if (response.data.status == "error") {
          alert(response.data.message);
          if ($callback) {
            $callback(false, { message: response.data.message });
          }
        } else {
          this.setDataFileManager(response.data);
          if ($callback) {
            $callback(true, {});
          }
        }
      })
      .catch((error) => {
        this.setDataFileManager({});
        if ($callback) {
          $callback(false, error);
        }
      });
  }
  getArrayFuncs() {
    return [...super.getArrayFuncs(), "$axios"];
  }
  render() {
    return `
    <div>
    <div class="fm-modal-overlay"></div>
      <div class="file-manager">
        <div class="fm-wrapper">
        [fm:Header /]
        [fm:Toolbar /]
        [fm:Body /]
        [fm:Footer /]
        </div>
      </div>
      [fm:CreateFolder /]
      [fm:UploadFile /]
    </div>
    `;
  }
}
// window.FileManager2 = FileManager.run(null, null, function () {
//   this.onCallback((data) => console.log(data));
// }).onDestroy(() => {
//   window.FileManager2 = null;
// });
