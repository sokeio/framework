import { Component } from "../sokeio/component";

export class PropertyInfo extends Component {
  state = {
    fileSelectedCount: 0,
  };
  init() {
    this.onReady(() => {
      this.$main.watch(
        "selectFiles",
        (newValue, oldValue, proValue) => {
          this.fileSelectedCount = this.$main.selectFiles?.length??0;
        },
        (destroy) => {
          this.onDestroy(() => {
            destroy();
          });
        }
      );
      this.watch("fileSelectedCount", (newValue, oldValue, proValue) => {
        if (newValue > 0) {
          this.setAttribute("style", "display: block;");
        } else {
          this.setAttribute("style", "display: none;");
        }
      });
    });
  }
  render() {
    return `<div class="property-wrapper" style="display: none;">
    Sitebar:demo
    </div>
      `;
  }
}
