export default {
  itemRender(item) {
    return `
         <div class="so-fm-item-box">
                            </div>
        `;
  },
  bodyGridRender() {
    let html = "";
    for (let i = 0; i < 600; i++) {
      html += this.itemRender();
    }
    return html;
  },
  render() {
    return `
                <div class="so-fm-body">
                    <div class="so-fm-folder-box">
                    </div>
                    <div class="so-fm-body-list">
                        <div class="so-fm-body-grid">
                            ${this.bodyGridRender()}
                        </div>
                    </div>
                </div>
        `;
  },
};
