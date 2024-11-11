export default {
  render() {
    return `
        <div class="so-fm-header">
            <div class="so-fm-header-title">
                <a href="https://sokeio.com" class="logo-large" target="_blank">
                    Sokeio FM V1.0
                </a>
                <a href="https://sokeio.com" class="logo-small" target="_blank">
                    SFM1.0
                </a>
            </div>
            <div class="so-fm-header-control">
                <div class="so-fm-header-control-item" so-on:click="$parent.createFolder()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-folder-plus"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">New Folder</div>
                </div>
                <div class="so-fm-header-control-item" so-on:click="$parent.uploadFile()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-upload"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">Upload File</div>
                </div>
               
                <div class="so-fm-header-control-item" so-on:click="$parent.refreshSelected()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-refresh"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">Refresh</div>
                </div>
            </div>
        </div>
        `;
  },
};
