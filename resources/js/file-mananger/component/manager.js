import body from "./body";
import header from "./header";
import footer from "./footer";

export default {
  components: {
    "so-fm::header": header,
    "so-fm::body": body,
    "so-fm::footer": footer,
  },
  state: {
    path: "/",
    files: [],
    folders: [],
  },
  boot() {
    this.cleanup(function () {});
    this.refreshSelected();
  },

  createFolder() {
    alert("create folder");
  },
  uploadFile() {
    alert("upload file");
  },
  deleteSelected() {
    alert("delete selected");
  },
  renameSelected() {
    alert("rename selected");
  },
  refreshSelected() {
    this.$request
      .post("/platform/file-manager", { path: this.path })
      .then((res) => {
        console.log(res);
      });
  },
  render() {
    return ` <div class="so-fm-wrapper">
          [so-fm::header /]
          [so-fm::body /]
          [so-fm::footer /]
        
        </div>`;
  },
};
