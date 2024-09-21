import header from "./header";

export default {
  components: {
    "so-filemanager::header": header,
  },
  boot() {
    document.body.classList.add("fm-body-wrapper");
    this.cleanup(function(){
      
    })
  },
  showFileManager(callback, type = "file") {},
  render() {
    return `<div class="so-fm">
        <div class="so-fm-overlay"></div>
        <div class="so-fm-application">
          [so-filemanager::header /]
        </div>
    </div>`;
  },
};
