export default {
  boot() {
    this.$parent.footerEl = this;
  },
  chooseIconAction() {
    if (!this.$parent.icon) return;
    this.$app.fnCallback(this.$parent.icon);
    this.closeApp();
  },
  render() {
    if (!this.$app?.fnCallback) {
      return "";
    }
    let html = `<div class="mt-1 p-2 d-flex flex-items-center flex-nowrap"><div class="flex-fill"></div>`;
    if (this.$parent.icon) {
      html += ` <div class="flex-1 me-4">Selected: <span class="fw-bold">${this.$parent.icon}</span> <i class=" ${this.$parent.icon} fs-2"></i></div>`;
    }
    html += `<button so-on:click="chooseIconAction()" class="btn btn-primary p-1"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg> Choose Icon</button>`;
    html += `</div>`;
    return html;
  },
};
