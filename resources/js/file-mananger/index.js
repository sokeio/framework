import { Application } from "../sokeio/application";
import { Body } from "./body";
import { File } from "./component/file";
import { Footer } from "./footer";
import { Header } from "./header";
import { Toolbar } from "./toolbar";

export class FileManager extends Application {
  components = {
    "fm:Header": Header,
    "fm:Toolbar": Toolbar,
    "fm:Body": Body,
    "fm:Footer": Footer,
    "fm:File": File,
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
    console.log({ fn: "selectFile", $field });
    if (this.selectFiles.includes($field)) {
      this.selectFiles = this.selectFiles.filter((item) => item !== $field);
    } else {
      this.selectFiles = [...this.selectFiles, $field];
    }
  }
  init() {}
  render() {
    return `
    <div class="file-manager">
      [fm:Header /]
      [fm:Toolbar /]
      [fm:Body /]
      [fm:Footer /]
    </div>
    `;
  }
}
window.FileManager2 = FileManager.run();
