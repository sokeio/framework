import { Component } from "../sokeio/component";

export class Body extends Component {
  state = {
    demo: 123,
  };
  init() {
    this.appInstance.watch(
      ["files", "folders"],
      (oldValue, newValue, proValue) => {
        this.reRender();
        console.log({
          body: { oldValue: oldValue, newValue: newValue, proValue: proValue },
        });
      }
    );
  }
  reRender() {
    console.log("reRender");
    this.clearChild();
    this.appEl.innerHTML = "";
    this.appInstance.files.forEach((file) => {
      let fileComponent = this.appInstance.getComponentByName(
        "fm:File",
        {},
        this
      );
      fileComponent.runComponent();
      this.appEl.appendChild(fileComponent.appEl);
    });
  }
  render() {
    return `
    <div class="body-wrapper">
     
    </div>
      `;
  }
}
