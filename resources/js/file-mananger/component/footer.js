export default {
  render() {
    return `
                <div class="so-fm-footer">
                        <div>Path:<span so-text="$parent.path" class="ps-1 fw-bold"></span></div>
                        <div class="flex-0">Selected File:<span so-text="$parent.selectedCount" class="ps-1 fw-bold"></span></div>
                        <div class="">File:<span so-text="$parent.fileCount" class="ps-1 fw-bold"></span></div>
                </div>
        `;
  },
};
