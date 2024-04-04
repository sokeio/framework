import { Component } from "../sokeio/component";

export class Body extends Component {
  state = {
    demo: 123,
  };
  init() {
    this.$main.watch(["files", "folders"], (newValue, oldValue, proValue) => {
      this.runRender();
    });
    this.onResize(() => {
      this.runRender();
    });
  }
  afterRender() {
    this.query(".body-content .box-wrapper", (el) => {
      el.innerHTML = "";
      el.style.opacity = 40 / 100;
      this.$main.files.forEach((file) => {
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
