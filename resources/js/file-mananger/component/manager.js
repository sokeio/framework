import header from "./header";

export default {
  components: {
    "so-filemanager::header": header,
  },
  boot() {
    this.cleanup(function () {});
  },
  render() {
    return ` <div class="so-fm-application">
          [so-filemanager::header /]
        </div>`;
  },
};
