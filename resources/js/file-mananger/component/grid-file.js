export default {
  itemRender(item) {
    return `
           <div class="so-fm-item-box">
                ${item.name}
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
  render() {
    return `
            <div class="so-fm-body-grid">
                ${this.bodyGridRender()}
            </div>
          `;
  },
};
