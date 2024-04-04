import { Component } from "../sokeio/component";

export class Body extends Component {
  state = {
    demo: 123,
  };
  init() {
    this.$main.watch(["files", "folders"], (newValue, oldValue, proValue) => {
      this.reRender();
      console.log({
        body: { oldValue: oldValue, newValue: newValue, proValue: proValue },
      });
    });
    this.onResize(() => {
      this.reRender();
    });
  }
  reRender() {
    this.clearChild();
    this.query(".body-content", (el) => {
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
    <div class="body-wrapper">
     <div class="body-content"></div>
    </div>
      `;
  }
}
