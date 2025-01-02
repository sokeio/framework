import { convertHtmlToElement } from "../utils";

export function getModalHtmlRender(
  html = "",
  footer = "",
  header = "",
  icon = "",
  component = ""
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
  let htmlClose = `<a class="so-modal-close" so-hotkey="esc" so-on:click="this.closeApp()"></a>`;
  let closeOverlay =
    'so-on:click="this.closeApp()" so-on:ignore=".so-modal-dialog"';
  let elHtml = convertHtmlToElement(html);
  let skipOverlayClose = component?.skipOverlayClose;

  let modalSize = component?.modalSize;
  if (component?.hideButtonClose) {
    htmlClose = "";
  }
  if (!modalSize && elHtml && elHtml.getAttribute && elHtml.querySelector) {
    if (
      elHtml.querySelector?.(".hide-show-button-close") ||
      elHtml.getAttribute("data-hide-button-close")
    ) {
      htmlClose = "";
    }
    if (!modalSize && elHtml.querySelector?.("[data-modal-size]")) {
      modalSize = elHtml
        .querySelector("[data-modal-size]")
        .getAttribute("data-modal-size");
    }
    if (
      skipOverlayClose === undefined &&
      elHtml.getAttribute("data-skip-overlay-close")
    ) {
      skipOverlayClose = elHtml.getAttribute("data-skip-overlay-close");
    }
    if (
      skipOverlayClose === undefined &&
      elHtml.querySelector?.("[data-skip-overlay-close]")
    ) {
      skipOverlayClose = elHtml
        .querySelector("[data-skip-overlay-close]")
        .getAttribute("data-skip-overlay-close");
    }
  }

  if (skipOverlayClose) {
    closeOverlay = "";
  }
  //
  if (!modalSize) {
    modalSize = "lg";
  }
  return `<div class="so-modal so-modal-size-${modalSize}" tabindex="-1" aria-modal="true" ${closeOverlay} >
                <div class="so-modal-dialog">
                    <div class="so-modal-content card">
                        ${$content}
                    </div>
                    ${htmlClose}
                </div>
            </div>`;
}
