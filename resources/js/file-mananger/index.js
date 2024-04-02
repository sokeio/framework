import { UI, ButtonUI, InputUI } from "../ui";

export class FileManager extends UI {
  init() {
    let $this = this;
    this.setState({
      folders: [],
      files: [],
      demo: 1,
      button1: null,
    });
    this.targetQuery(".toolbar-wrapper .toolbar-button", [
      ButtonUI.make("Add Folder 1")
        .on("click", () => {
          console.log($this.$data.demo);
          $this.$data.button1.text("Add Folder " + $this.$data.demo);
        })
        .ready(($button) => {
          $this.$data.button1 = $button;
        }),
      ButtonUI.make("Add Folder +1").on("click", () => {
        $this.$data.demo = $this.$data.demo + 1;
      }),
      ButtonUI.make("Add Folder +2").on("click", () => {
        $this.$data.demo = $this.$data.demo + 1;
      }),
      ButtonUI.make("Add Folder").on("click", () => {
        alert("Add Folder");
      }),
    ]);
    this.targetQuery(".toolbar-wrapper .toolbar-search", [
      InputUI.make()
        .attr("placeholder", "Search")
        .addClass("search-input")
        .on("keypress", (e) => {
          console.log(e.target.value);
        }),
      ButtonUI.make("Search").on("click", () => {
        alert("Search");
      }),
    ]);

    this.ready(($this) => {
      $this.$watch("demo", (prevValue, nextValue) => {
        $this.$data.button1.text("Text " + $this.$data.demo);
        console.log({ demo: "watch", prevValue, nextValue });
      });
      $this.queryOn(".search-input", "keyup", (e) => {
        console.log(e.target.value);
        $this.$data.demo = e.target.value;
      });
    });
  }
  template() {
    return `<div class="file-manager" tabindex="-1" style="display: block;">
    <div class="toolbar-wrapper">
      <div class="toolbar-button">
      </div>
      <div class="toolbar-search">
      </div>
    </div>
    <div class="fm-wrapper">
    </div>
  </div>`;
  }
}
window.FileManager2 = new FileManager(null);
// window.FileManager2.render();
