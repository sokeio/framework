import { Component } from "../sokeio/component";

export class Toolbar extends Component {
  state = {
    searchText: {
      demo: "",
    },
  };
  init() {
    this.$main.watch(
      ["selectFiles", "selectFolders"],
      (newValue, oldValue, proValue) => {
        if (
          this.$main.selectFiles.length > 0 ||
          this.$main.selectFolders.length > 0
        ) {
          this.query(".btn-item-selected", (el) => {
            el.removeAttribute("disabled");
          });
        } else {
          this.query(".btn-item-selected", (el) => {
            el.setAttribute("disabled", true);
          });
        }
      }
    );
  }
  doSearch() {}
  showCreateFolder() {
    let elInputText = this.$main.inputText("Create Folder", "", (data) => {
      this.$main.actionManager("createFolder", { name: data }, (rs) => {
        if (rs) {
          elInputText.doClose();
        }
      });
    });
  }
  showUploadFile() {
    this.$main.showUploadFile();
  }
  deleteSelected() {
    console.log("deleteSelected");
  }
  downloadSelected() {
    console.log("downloadSelected");
  }
  refreshData() {
    this.$main.refreshData();
  }
  render() {
    return `
    <div class="fm-toolbar">
      <div class="toolbar-button">
        <button class="btn btn-primary" s-on:click="this.refreshData()"><i class="ti ti-refresh"></i> <span>Refresh</span></button>
        <button class="btn btn-primary" s-on:click="this.showCreateFolder()"><i class="ti ti-folder-plus"></i> <span>Create Folder</span></button>
        <button class="btn btn-primary" s-on:click="this.showUploadFile()"><i class="ti ti-upload"></i><span> Upload</span></button>
        <button class="btn btn-primary d-none" s-on:click="this.downloadSelected()"><i class="ti ti-download"></i><span> Download</span></button>
        <button class="btn btn-primary btn-item-selected d-none" disabled s-on:click="this.deleteSelected()"><i class="ti ti-trash"></i><span> Delete</span></button>
      </div>
      <div class="toolbar-search">
        <input s-model="searchText.demo" s-on:enter="this.doSearch()" type="text" class="form-control  d-none" placeholder="Search">
        <button s-on:click="this.doSearch()" class="btn btn-primary  d-none">Search</button>
      </div>
    </div>
      `;
  }
}
