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
    this.$main.files = [
      ...this.$main.files,
      ...[
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
      ].map((item) => {
        return {
          fileId: item + this.$main.files.length,
          fileName: "abc_" + this.$main.files.length + item,
          isFolder: true,
        };
      }),
    ];
  }
  render() {
    return `
    <div class="fm-toolbar">
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
