import { Application } from "../sokeio/application";
import { downloadFile } from "../utils";
import { Body } from "./body";
import { File } from "./component/file";
import { Folder } from "./component/folder";
import { ItemBack } from "./component/item-back";
import { CtxMenu } from "./ctxmenu";
import { Footer } from "./footer";
import { Header } from "./header";
import { Confirm } from "./modal/confirm";
import { EditImage } from "./modal/edit-image";
import { InputText } from "./modal/input-text";
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
    "fm:InputText": InputText,
    "fm:Confirm": Confirm,
    "fm:UploadFile": UploadFile,
    "fm:ItemBack": ItemBack,
    "fm:EditImage": EditImage,
    "fm:CtxMenu": CtxMenu,
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
  $contextMenu;
  cast = {
    // demo: (v) => parseInt(v),
  };

  UploadFile(
    files,
    $initData = undefined,
    $callback = undefined,
    onUploadProgress = undefined
  ) {
    let formData = new FormData();
    if (!Array.isArray(files)) {
      files = [files];
    }
    for (let file of files) {
      formData.append("files[]", file);
    }
    formData.append("disk", this.disk);
    formData.append("path", this.path);
    if ($initData && typeof $initData === "function") {
      $initData(formData);
    } else if ($initData && typeof $initData === "object") {
      for (let key in $initData) {
        formData.append(key, $initData[key]);
      }
    }
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
  editImage($item) {
    let editItem = this.getComponentByName(
      "fm:EditImage",
      {
        item: $item,
        onSave: (data) => {
          this.UploadFile(
            [],
            (formData) => {
              formData.append("nameOld", data.nameOld);
              formData.append("files", data.file, data.name);
            },
            (rs, data) => {
              this.refreshData();
            }
          );
        },
      },
      this
    );
    editItem.runComponent();
    this.$el.appendChild(editItem.$el);
    return editItem;
  }
  inputText(title, text, callback) {
    let editItem = this.getComponentByName(
      "fm:InputText",
      {
        title,
        text,
        onSave: (data) => {
          this.runTimeout(() => callback(data), "inputText");
        },
      },
      this
    );
    editItem.runComponent();
    this.$el.appendChild(editItem.$el);
    return editItem;
  }
  confirm(title, text, callback) {
    let editItem = this.getComponentByName(
      "fm:Confirm",
      {
        title,
        text,
        onSave: (data) => {
          this.runTimeout(() => callback(data), "confirm");
        },
      },
      this
    );
    editItem.runComponent();
    this.$el.appendChild(editItem.$el);
    return editItem;
  }
  downloadFile($item) {
    if ($item.type === "folder") {
      return;
    }
    downloadFile($item.url, $item.name);
  }
  selectFile($file) {
    if (!this.selectFiles.includes($file)) {
      this.selectFiles = [...this.selectFiles, $file];
    }
    this.currentFolder = null;
    this.currentFile = $file;
  }
  touchFile($file, flg = false) {
    this.selectFolders = [];
    this.currentFolder = null;
    this.currentFile = $file;
    if (this.selectFiles.includes($file) && !flg) {
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
  touchFolder($folder, flg = false) {
    this.selectFiles = [];
    this.currentFile = null;
    this.currentFolder = $folder;
    if (this.selectFolders.includes($folder) && !flg) {
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
    if (this.$callbackEvent) {
      this.$callbackEvent(false);
    }
  }
  isCallback() {
    return !!this.$callbackEvent;
  }
  onCallback($callback) {
    this.$callbackEvent = $callback;
    return this;
  }
  selectOk() {
    if (this.$callbackEvent) {
      this.$callbackEvent(this.selectFiles);
    }
    this.closeApp();
  }
  init() {
    this.onReady(() => {
      this.bodyOverflowHide("fm-body-overflow-hide");
      if (window.SokeioManager) {
        this.$axios = window.SokeioManager.getAxios();
        if (this.$axios) {
          this.refreshData();
        }
      }
      this.$contextMenu = this.getComponentByName("fm:CtxMenu", {}, this);
      this.$contextMenu.runComponent();
      this.$el.appendChild(this.$contextMenu.$el);
    });
    window.addEventListener("sokeio::ready", (e) => {
      this.$axios = window.SokeioManager.getAxios();
      this.refreshData();
    });
  }
  refreshData() {
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
      [fm:UploadFile /]
    </div>
    `;
  }
}
