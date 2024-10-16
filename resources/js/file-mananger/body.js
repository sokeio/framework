import { Component } from "../sokeio/component";

export class Body extends Component {
  state = {};
  init() {
    this.$main.watch(["files", "folders"], (newValue, oldValue, proValue) => {
      this.refreshUI();
    });
    this.on("contextmenu", (e) => {
      e.preventDefault();
    });
  }
  refreshUI() {
    this.runTimeout(() => {
      this.runRender();
    }, "refreshUI");
  }
  afterRender() {
    this.query(".box-wrapper", (el) => {
      el.innerHTML = "";
      el.style.opacity = 40 / 100;
      if (this.$main.path !== "/" && this.$main.path !== "") {
        let itemBackComponent = this.$main.getComponentByName(
          "fm:ItemBack",
          {},
          this
        );
        itemBackComponent.runComponent();
        el.appendChild(itemBackComponent.$el);
      }

      this.$main.folders?.forEach((folder) => {
        let folderComponent = this.$main.getComponentByName(
          "fm:Folder",
          { folder },
          this
        );
        folderComponent.runComponent();
        el.appendChild(folderComponent.$el);
      });
      this.$main.files?.forEach((file) => {
        let fileComponent = this.$main.getComponentByName(
          "fm:File",
          { file },
          this
        );
        fileComponent.runComponent();
        el.appendChild(fileComponent.$el);
      });
      el.style.opacity = "";
    });
  }
  render() {
    return `
    <div class="fm-body">
    <ul class="box-wrapper"></ul>
      [fm:ItemInfo /]
    </div>
      `;
  }
}
