import { Component } from "../sokeio/component";

export class Toolbar extends Component {
  state = {
    searchText: {
      demo: "",
    },
  };
  init() {}
  doSearch() {}
  showCreateFolder() {
    this.$main.isCreateFolder = true;
  }
  showUploadFile() {
    this.$main.isUploadFile = true;
  }
  backFolder() {
    this.$main.backFolder();
  }

  render() {
    return `
    <div class="fm-toolbar">
      <div class="toolbar-button">
      <button class="btn btn-primary" s-on:click="this.backFolder()">Back</button>
        <button class="btn btn-primary" s-on:click="this.showCreateFolder()">Create Folder</button>
        <button class="btn btn-primary" s-on:click="this.showUploadFile()">Upload</button>
      </div>
      <div class="toolbar-search">
        <input s-model="searchText.demo" s-on:enter="this.doSearch()" type="text" class="form-control" placeholder="Search">
        <button s-on:click="this.doSearch()" class="btn btn-primary">Search</button>
      </div>
    </div>
      `;
  }
}
