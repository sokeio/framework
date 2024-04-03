import { Component } from "../sokeio/component";

export class Toolbar extends Component {
  state = {
    searchText: "123",
  };
  init() {}
  doSearch() {
    alert(this.searchText);
  }
  doCreateFolder() {
    this.appInstance.files = [...this.appInstance.files, { file: "abc" }];
  }
  render() {
    return `
    <div class="toolbar-wrapper">
      <div class="toolbar-button">
        <button class="btn btn-primary" s-on:click="this.doCreateFolder()">Create Folder</button>
        <button class="btn btn-primary">Upload</button>
        <button class="btn btn-primary">Upload</button>
        <button class="btn btn-primary">Upload</button>
      </div>
      <div class="toolbar-search">
        <input s-model="searchText" s-on:enter="this.doSearch()" type="text" class="form-control" placeholder="Search">
        <button s-on:click="this.doSearch()" class="btn btn-primary">Search</button>
      </div>
    </div>
      `;
  }
}
