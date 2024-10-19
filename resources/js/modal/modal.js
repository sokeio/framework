import * as Utils from "./utils";
export default {
  state: { html: "", loading: true },
  boot() {
    if (!this.skipLoading || !this.loading) {
      let html = Utils.getModalOverlay();
      this.cleanup(function () {
        document.body.removeChild(html);
        // this.html = "";
      });
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
    if (this.url.includes("?")) {
      this.url =
        this.url + "&refId=" + refId + "&_time=" + new Date().getTime();
    } else {
      this.url =
        this.url + "?refId=" + refId + "&_time=" + new Date().getTime();
    }
    console.log(this.url);
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
    return Utils.getModalHtmlRender(
      this.html ||
        '<div class="so-modal-loader" data-hide-close="true"><span class="loader"></span></div>',
      "",
      "",
      this.icon
    );
  },
};
