import { Component } from "../../sokeio/component";

export class ItemBack extends Component {
  state = {};
  init() {}
  backFolder() {
    this.$main.backFolder();
  }
  render() {
    return `
    <li class="item-box">
      <div class="file-box item-wrapper"  s-on:click="this.backFolder()" >
        <div class="item-body"><i class="ti ti-placeholder" style="font-size: 96px;"></i></div>
        <div class="item-name">...</div>
      </div>
    </li>
      `;
  }
}