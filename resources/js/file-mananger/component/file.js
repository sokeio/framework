import { Component } from "../../sokeio/component";

export class File extends Component {
  state = {
    selected: false,
  };
  init() {
    this.watch("selected", () => {
      if (this.selected) {
        this.appEl.classList.add("item-active");
      } else {
        this.appEl.classList.remove("item-active");
      }
    });
  }

  touchFile() {
    console.log("touchFile" + new Date());
    this.selected = this.selected ? false : true;
  }
  render() {
    return `
    <div class="item-box">
        <div class="file-box" s-on:click="this.touchFile()">
        File
        </div>
    </div>
      `;
  }
}
