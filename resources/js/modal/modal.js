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
  return `<div class="so-modal" tabindex="-1" aria-modal="true" so-on:click="this.delete()" so-on:ignore=".so-modal-dialog">
              <div class="so-modal-dialog">
                  <div class="so-modal-content">
                      <a class="so-modal-close" so-on:click="this.delete()"></a>
                     
                      ${header ?? ""}
                      ${html ? `<div class="so-modal-body">${html}</div>` : ""}
                      ${
                        footer
                          ? `<div class="so-modal-footer">${footer}</div>`
                          : ""
                      }
                  </div>
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
  state: {},
  boot() {
    let html = getModalOverlay();
    this.cleanup(function () {
      document.body.removeChild(html);
    });
  },
  ready() {},
  render() {
    return getModalHtmlRender("", "", "", this.icon);
  },
};
