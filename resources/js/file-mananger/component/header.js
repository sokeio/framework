export default {
  itemClick(item) {
    this.$parent.toolbars[item].action.bind(this.$parent)();
  },
  itemRender() {
    let html = "";
    this.$parent.toolbars.forEach((item, key) => {
      html += `
            <div class="so-fm-header-control-item" so-on:click="itemClick(${key})">
                    <div class="so-fm-header-control-item-icon">
                        <i class="${item.icon}"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">${item.title}</div>
                </div>
            
            `;
    });
    return html;
  },
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
  ${this.itemRender()}
            </div>
        </div>
        `;
  },
};
