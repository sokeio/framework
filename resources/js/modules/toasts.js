import { SokeioPlugin } from "../core/plugin";

export class ToastsModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_TOASTS_MODULE";
  }
  initPosition() {
    let self = this;
    Object.keys(self.postion).forEach((key) => {
      self.postion_el[key] = self
        .getManager()
        .appendHtmlToBody(
          `<div style='position: fixed;' class="toast-container p-3 ${self.postion[key]}" id="toast-${key}">`
        );
    });
  }
  booting() {
    let self = this;
    self.getManager().removeListenerAll("sokeio::message");
    self
      .getManager()
      .onSafe("sokeio::message", self.showMessageEvent.bind(this));

    document.addEventListener("livewire:navigated", () => {
      self.initPosition();
    });
  }
  postion = {
    top_left: "top-0 start-0",
    top_center: "top-0 start-50 translate-middle-x",
    top_right: "top-0 end-0",
    middle_left: "top-50 start-0",
    middle_center: "top-50 start-50 translate-middle-x",
    middle_right: "top-50 end-0",
    bottom_left: "bottom-0 start-0",
    bottom_center: "bottom-0 start-50 translate-middle-x",
    bottom_right: "bottom-0 end-0",
  };
  postion_el = {};
  addToast(
    content,
    postion = "bottom_right",
    title = undefined,
    metaType = undefined,
    autohide = true,
    onCallback = undefined
  ) {
    let self = this;
    let toastContainer = this.postion_el["bottom_right"];
    if (postion && this.postion_el[postion]) {
      toastContainer = this.postion_el[postion];
    }
    const contenthtml = title
      ? `
    <div class="toast ${
      metaType ?? ""
    }" role="alert" aria-live="assertive" aria-atomic="true" ${
          autohide !== true ? 'data-bs-autohide="false"' : ""
        } >
    <div class="toast-header">
      <strong class="me-auto">${title}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
     ${content}
    </div>
  </div>`
      : `
  <div class="toast align-items-center ${
    metaType ?? ""
  }" role="alert" aria-live="assertive" aria-atomic="true" ${
          autohide !== true ? 'data-bs-autohide="false"' : ""
        }>
  <div class="d-flex">
    <div class="toast-body">
    ${content}
    </div>
    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>`;
    const toastEl = self.getManager().htmlToElement(contenthtml.trim());
    toastContainer.appendChild(toastEl);
    const toast = new globalThis.bootstrap.Toast(toastEl);
    toast.show();
    if (onCallback) {
      if (typeof onCallback === "string") {
        window.eval(onCallback)(toastEl, toast);
      } else {
        onCallback(toastEl, toast);
      }
    }
  }
  showMessageEvent = (el) => {
    const { message, meta, type } = el;
    const { postion, title, type: metaType, autohide, onCallback } = meta;
    this.addToast(
      message,
      postion,
      title,
      metaType ?? type,
      autohide,
      onCallback
    );
  };
}
