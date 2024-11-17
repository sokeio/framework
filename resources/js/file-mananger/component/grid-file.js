export default {
  itemRender(item) {
    return `
           <div class="so-fm-item-box ${
             this.$parent.checkItemActive(item.path) ? "active" : ""
           }" so-on:click='chooseFile("${item.path}")'>
            <div class="so-fm-item-box-preview">
                  <img src="${item.preview_url}" alt="${item.name}"/>
              </div>
              <div class="so-fm-item-box-name">
                  ${item.name}
              </div>
           </div>
          `;
  },
  bodyGridRender() {
    let html = "";
    this.$parent.files.forEach((item) => {
      html += this.itemRender(item);
    });

    return html;
  },
  chooseFile(path, multiple = false) {
    this.$parent.chooseFile(path, multiple);
    this.refresh();
  },
  render() {
    return `
            <div class="so-fm-body-grid">
                ${this.bodyGridRender()}
            </div>
          `;
  },
};
