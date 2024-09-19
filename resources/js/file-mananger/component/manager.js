import header from "./header";

export default {
  components: {
    "so-filemanager::header": header,
  },
  render() {
    return `<div class="so-file-manager">
        [so-filemanager::header /]
    </div>`;
  },
};
