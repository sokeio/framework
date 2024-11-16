const messageComponent = {
  state: { html: "", loading: true },
  ready() {
    if (this.timeout > 20000) {
      this.timeout = 7000;
    }
    setTimeout(() => {
      this.delete();
    }, this.timeout ?? 5000);
  },
  render() {
    let $messageType = "";
    if (this.messageType) {
      $messageType;
    }
    return `
      <div class="alert ${$messageType} so-alert" role="alert">
          <div>
            <h4 class="alert-title" so-text="title"></h4>
            <div class="alert-message"  so-text="message"></div>
          </div>
        </div>
      </div
      `;
  },
};

const messagePayload = (payload) => {
  if (!document.querySelector(".so-position-manager")) {
    position();
  }
  setTimeout(() => {
    let position = payload.position ?? "top-right";
    let elPosition = document.querySelector(
      `.so-position-manager .so-position-${position}`
    );
    if (!elPosition) {
      elPosition = document.querySelector(
        `.so-position-manager .so-position-bottom-right`
      );
    }
    if (elPosition.children.length > 9) {
      elPosition.children[0].__sokeio?.delete?.();
    }
    window.sokeioUI.run(messageComponent, {
      props: { ...payload },
      selector: elPosition,
    });
  });
};
window.sokeioMessage = messagePayload;
let position = () => {
  let html = `
    <div class="so-position-manager">
        <div class="so-position so-position-top-left"></div>
        <div class="so-position so-position-top-center"></div>
        <div class="so-position so-position-top-right"></div>
        <div class="so-position so-position-middle-left"></div>
        <div class="so-position so-position-center-center"></div>
        <div class="so-position so-position-middle-right"></div>
        <div class="so-position so-position-bottom-left"></div>
        <div class="so-position so-position-bottom-center"></div>
        <div class="so-position so-position-bottom-right"></div>
    </div>
    `;
  document.body.insertAdjacentHTML("beforeend", html);
};
