import { Utils } from "../framework/common/Uitls";

export default {
  state: {
    title: "ac",
  },
  boot() {
    // this.on(".so-modal-overlay", "click", function () {
    //   alert("1");
    // });
    // this.on(".so-modal-close", this.closeModal);
    let html = Utils.convertHtmlToElement(
      '<div class="so-modal-overlay"></div>'
    );
    let elOverlay = document.body.querySelector(".so-modal-overlay");
    if (elOverlay) {
      elOverlay.parentNode.insertBefore(html, elOverlay);
    } else {
      document.body.appendChild(html);
    }
    html.addEventListener("click", this.closeModal.bind(this));
    this.cleanup(function () {
      document.body.removeChild(html);
    });
  },
  closeModal() {
    this.delete();
  },
  showModal(url, data, callback, type) {},
  ready() {},
  render() {
    return `<div class="so-modal" tabindex="-1" aria-modal="true" so-on:click="closeModal()" so-on:ignore=".so-modal-dialog">
                <div class="so-modal-dialog">
                    <div class="so-modal-content">
                        <div class="so-modal-header">
                            <h3 class="so-modal-title" so-text="title"></h3>
                            <a class="so-modal-close" so-on:click="closeModal()"></a>
                        </div>
                        <div class="so-modal-body">
                            <input so-model="title"/>
                        </div>
                        <div class="so-modal-footer"></div>
                    </div>
                </div>
            </div>
    `;
  },
};
