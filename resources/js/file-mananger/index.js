import { Application } from "../sokeio/application";
import { Body } from "./body";
import { File } from "./component/file";
import { Footer } from "./footer";
import { Header } from "./header";
import { PropertyInfo } from "./property";
import { Toolbar } from "./toolbar";

export class FileManager extends Application {
  components = {
    "fm:Header": Header,
    "fm:Toolbar": Toolbar,
    "fm:Body": Body,
    "fm:Footer": Footer,
    "fm:File": File,
    "fm:ItemInfo": PropertyInfo,
  };
  state = {
    files: [],
    folders: [],
    selectFiles: [],
    selectFolders: [],
  };
  cast = {
    // demo: (v) => parseInt(v),
  };
  selectFile($field) {
    if (!this.selectFiles.includes($field)) {
      this.selectFiles = [...this.selectFiles, $field];
    }
  }
  touchFile($field) {
    if (this.selectFiles.includes($field)) {
      this.selectFiles = [];
    } else {
      this.selectFiles = [$field];
    }
  }
  closeApp() {
    this.destroy();
  }
  init() {}
  render() {
    return `
    <div class="file-manager">
      <div class="fm-wrapper">
      [fm:Header /]
      [fm:Toolbar /]
      [fm:Body /]
      [fm:Footer /]
      </div>
    </div>
    `;
  }
}
window.FileManager2 = FileManager.run().onDestroy(() => {
  window.FileManager2 = null;
});
