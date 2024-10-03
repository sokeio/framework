import { Utils } from "../framework/common/Uitls";
export function getModalHtmlRender(
  html = "",
  footer = "",
  header = "",
  icon = ""
) {
  // check icon with tags or class
  if (icon.indexOf("<") === -1) {
    // check icon with tags
    icon = `<i class="${icon}"></i>`;
  }
  icon = icon.replace('class="', 'class="so-modal-icon ');
  if (header === undefined) {
    header = ` <div class="so-modal-header">${icon}
                          <h3 class="so-modal-title" so-text="title"></h3>
                      </div>`;
  }
  $content = header;
  if (!footer && !$content) {
    $content = html;
  } else {
    $content = `${$conten}<div class="so-modal-body">${html}</div> ${
      footer ? `<div class="so-modal-footer">${footer}</div>` : ""
    }`;
  }
  let htmlClose = `<a class="so-modal-close" so-on:click="this.delete()"></a>`;
  let closeOverlay =
    'so-on:click="this.delete()" so-on:ignore=".so-modal-dialog"';
  let elHtml = Utils.convertHtmlToElement(html);
  if (elHtml.querySelector(".skip-show-close")) {
    htmlClose = "";
  }
  // attribute data-modal-size
  let modalSize = elHtml.getAttribute("data-modal-size");
  if (!modalSize && elHtml.querySelector("[data-modal-size]")) {
    modalSize = elHtml
      .querySelector("[data-modal-size]")
      .getAttribute("data-modal-size");
  }
  let skipOverlay = elHtml.getAttribute("data-skip-overlay");
  if (!skipOverlay && elHtml.querySelector("[data-skip-overlay]")) {
    skipOverlay = elHtml
      .querySelector("[data-skip-overlay]")
      .getAttribute("data-skip-overlay");
  }
  if (skipOverlay) {
    closeOverlay = "";
  }
  //
  if (!modalSize) {
    modalSize = "lg";
  }
  return `<div class="so-modal so-modal-size-${modalSize}" tabindex="-1" aria-modal="true" ${closeOverlay} >
              <div class="so-modal-dialog">
                  <div class="so-modal-content">
                      ${$content}
                  </div>
                  ${htmlClose}
              </div>
          </div>`;
}
export function getModalOverlay() {
  let html = Utils.convertHtmlToElement('<div class="so-modal-overlay"></div>');
  let elOverlay = document.body.querySelector(".so-modal-overlay");
  if (elOverlay) {
    elOverlay.parentNode.insertBefore(html, elOverlay);
  } else {
    document.body.appendChild(html);
  }
  return html;
}
export default {
  state: { html: "", loading: true },
  boot() {
    if (!this.skipLoading || !this.loading) {
      let html = getModalOverlay();
      this.cleanup(function () {
        document.body.removeChild(html);
      });
    }

    if (this.html) {
      return;
    }
    this.$request.get(this.url).then(async (res) => {
      if (!res.ok) {
        this.html = `<div class="so-modal-content-error"><h3>${res.statusText}</h3><button class="btn btn-primary" so-on:click="this.delete()">Close</button></div>`;
      } else {
        this.html = await res.text();
      }

      this.loading = false;
      this.reRender();
    });
  },
  ready() {},

  render() {
    if (this.skipLoading && this.loading) {
      return "<template></template>";
    }
    return getModalHtmlRender(
      this.html ||
        '<div class="so-modal-loader"><span class="loader"></span></div>',
      "",
      "",
      this.icon
    );
  },
};
