export default {
  components: {},
  state: {},
  itemClick(item) {
    let itemContext = this.$parent.toolbar[item];
    if (itemContext) {
      if (itemContext.action) {
        let func = new Function(`return function(item){
            ${itemContext.action}
          }`)();
        func.bind(this.$parent)(itemContext);
      }
      if (itemContext.view && this.$parent.views[itemContext.view]) {
        let viewOptions = itemContext.viewOptions || {};
        window.showModal(itemContext.title, {
          template: window.sokeioUI.textScriptToJs(
            this.$parent.views[itemContext.view]
          ),
          data: {
            ...viewOptions,
            item: itemContext,
            mediaStorage: this.$parent,
          },
        });
      }
    }
  },
  toolbarRender() {
    let html = "";
    this.$parent.toolbar.forEach((item, key) => {
      html += `<div class="so-media-storage-header-control-item" so-on:click="itemClick(${key})">
                      <div class="so-media-storage-header-control-item-icon">
                          <i class="${item.icon}"></i>
                      </div>
                      <div class="so-media-storage-header-control-item-text">${item.name}</div>
                  </div>`;
    });
    return html;
  },
  render() {
    return `
      <div class="so-media-storage-header">
                    <div class="so-media-storage-header-title so-logo">
                        <a href="https://sokeio.com" class="logo-large" target="_blank">  
                            Sokeio FM V1.0
                        </a>
                        <a href="https://sokeio.com" class="logo-small" target="_blank">
                            SFM1.0
                        </a>
                    </div>
                    <div class="so-media-storage-header-control">
                        <div class="so-media-storage-header-control-item" so-on:click="$parent.mediaAction('refresh')">
                            <div class="so-media-storage-header-control-item-icon">
                                <i class="ti ti-refresh"></i>
                            </div>
                            <div class="so-media-storage-header-control-item-text">Refresh</div>
                        </div>
                        ${this.toolbarRender()}
                    </div>
                </div>
      `;
  },
};
