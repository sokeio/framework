import { Component } from "../sokeio/component";

export class Header extends Component {
  state = {};
  CloseApp() {
    alert("CloseApp");
  }
  render() {
    return `
    <div class="header-wrapper">
        <div class="header-title">
            <h1>File Manager</h1>
        </div>
        <div class="header-button">
            <button class="btn btn-close" s-on:click="this.CloseApp()"></button>
        </div>

    </div>
      `;
  }
}
