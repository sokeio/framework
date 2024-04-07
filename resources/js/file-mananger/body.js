import { Component } from "../sokeio/component";

export class Body extends Component {
  state = {
    demo: 123,
    widthBox: 200,
  };
  init() {
    this.$main.watch(["files", "folders"], (newValue, oldValue, proValue) => {
      this.runRender();
    });
    this.onResize(() => {
      this.runRender();
    });
    this.onReady(() => {
      this.callWithBox();
    });
  }
  _timer = null;
  callWithBox() {
    this.query(".body-content .box-wrapper", (el) => {
      if (this._timer) {
        clearTimeout(this._timer);
      }
      this._timer = setTimeout(() => {
        const wrapperWidth = el.offsetWidth;
        const minBoxWidth = 180;

        let rs = wrapperWidth / minBoxWidth;
        let rsInt = parseInt(rs);
        if (parseFloat(rsInt) + 0.6 < rs) {
          rsInt = rsInt + 1;
        }
        let widthBox = parseInt(wrapperWidth / rsInt) - 15;

        this.widthBox = widthBox;
        this._timer = null;
      }, 0);
    });
  }
  afterRender() {
    this.query(".body-content .box-wrapper", (el) => {
      el.innerHTML = "";
      el.style.opacity = 40 / 100;
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
      <div class="body-content">
        <div class="box-wrapper"></div>
      </div>
      [fm:ItemInfo /]
    </div>
      `;
  }
}
