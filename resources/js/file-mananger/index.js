import { Application } from "../sokeio/application";
import { Demo } from "./demo";

export class FileManager extends Application {
  state = {
    demo: 123,
  };
  cast = {
    demo: (v) => parseInt(v),
  };
  init() {
    this.watch("demo", () => {
      console.log(this.state.demo);
      this.setText("#fm", this.state.demo);
    });
    this.on("#open-file-manager", "click", () => {
      this.demo = this.demo + 1;
    });
    this.registerComponent("demo:abc", Demo);
  }
  render() {
    return `
    <div class="file-manager">
      <div class="container">
        [demo:abc abc="12344" act="12222"/]
        <h1>File Manager</h1>
        [demo:abc /]
        <input s-model="demo" type="text">
        <div id="fm"></div>
        [demo:abc /]
        <button class="btn btn-primary" id="open-file-manager">Open</button>
      </div>
    </div>
    `;
  }
}
window.FileManager2 = FileManager.make();
window.FileManager2.run();
