export default {
  boot() {
    document.body.classList.add("body-overflow-hide");
    this.cleanup(function () {});
  },
  showModal(url, data, callback, type) {},
  render() {
    return `<div class="so-modal">
                <div class="so-modal-overlay"></div>
                <div class="so-modal-wrapper">
                    <div class="so-modal-body">
                        <div class="so-modal-header"></div>
                        <div class="so-modal-content">Demo</div>
                        <div class="so-modal-footer"></div>
                    </div>
                </div>
            </div>
    `;
  },
};
