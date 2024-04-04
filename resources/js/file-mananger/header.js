import { Component } from "../sokeio/component";

export class Header extends Component {
  state = {};
  closeApp() {
    this.$main.closeApp();
  }
  render() {
    return `
    <div class="fm-header">
        <div class="header-title">
            <h1>File Manager</h1>
        </div>
        <div class="header-button">
            <button class="btn btn-close" s-on:click="this.closeApp()"></button>
        </div>

    </div>
      `;
  }
}
