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
  };
  cast = {
    // demo: (v) => parseInt(v),
  };
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
// window.FileManager2 = FileManager.run();
