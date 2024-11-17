import { logDebug } from "../utils";
import * as Utils from "./utils";
export default {
  state: { html: "", loading: true },
  boot() {
    logDebug("modal.components", this.components);
    if (this.htmlComponent) {
      logDebug("modal.htmlComponent", this.htmlComponent);
      this.loading = false;
      this.html = this.htmlComponent;
    }
    if (this.html) {
      return;
    }
    let refId = "";
    if (this.elTarget) {
      if (this.elTarget.hasAttribute("wire:id")) {
        refId = this.elTarget.getAttribute("wire:id");
      } else {
        refId = this.elTarget.closest("[wire\\:id]")?.getAttribute("wire:id");
      }
    }
    if (!this.url) {
      return;
    }
    if (this.url.includes("?")) {
      this.url =
        this.url + "&refId=" + refId + "&_time=" + new Date().getTime();
    } else {
      this.url =
        this.url + "?refId=" + refId + "&_time=" + new Date().getTime();
    }
    logDebug("modal.url", this.url);
    this.$request.get(this.url).then(async (res) => {
      if (!res.ok) {
        this.html = `<div class="so-modal-content-error"><h3>${res.statusText}</h3><button class="btn btn-primary" so-on:click="this.closeApp()">Close</button></div>`;
      } else {
        this.html = await res.text();
      }

      this.loading = false;
      this.refresh();
    });
  },
  ready() {},

  render() {
    logDebug("modal.render", this.html);
    if (this.skipLoading && this.loading) {
      return "<template></template>";
    }
    return Utils.getModalHtmlRender(
      this.html ||
        '<div class="so-modal-loader" data-hide-close="true"><span class="loader"></span></div>',
      "",
      "",
      this.icon,
      this
    );
  },
};
