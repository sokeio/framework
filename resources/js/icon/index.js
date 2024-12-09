import list from "./list.js";
export default {
  components: {
    "list-icon": list,
  },
  state: {
    tabIndex: 1,
    icon: "",
    textSearch: "",
    selectionStart: null,
    selectionEnd: null,
    listElTop: 0,
  },
  listEl: null,
  textSearchEl: null,
  onScroll(e) {
    //TODO: Fix Scroll
    // this.listElTop = e.target.scrollTop;
    // console.log(this.listElTop);
    // console.log(e);
  },
  boot() {
    this.watch("tabIndex", (value) => {
      this.listEl.refresh();
    });
    this.watch("icon", (value) => {
      this.refresh();
    });
    this.watch("textSearch", (value) => {
      this.listEl.refresh();
    });
    this.ready(function () {
      this.textSearchEl.focus();
    });
  },
  chooseIcon(icon) {
    this.icon = icon;
  },
  chooseIconAction() {
    if (!this.icon) return;
    this.$app.fnCallback(this.icon);
    this.closeApp();
  },
  footerRender() {
    if (!this.$app?.fnCallback) {
      return "";
    }
    let html = `<div class="mt-1 p-2 d-flex flex-items-center flex-nowrap"><div class="flex-fill"></div>`;
    if (this.icon) {
      html += ` <div class="flex-1 me-4">Selected: <span class="fw-bold">${this.icon}</span> <i class=" ${this.icon} fs-2"></i></div>`;
    }
    html += `<button so-on:click="chooseIconAction()" class="btn btn-primary p-1"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg> Choose Icon</button>`;
    html += `</div>`;
    return html;
  },
  render() {
    return `
               <div>
                    <div class="d-flex bg-white">
                        <div so-on:click="tabIndex=1" class="p-2 m-1 ${
                          this.tabIndex == 0
                            ? "bg-cyan text-bg-cyan"
                            : "bg-warning text-bg-warning"
                        } cursor-pointer hover" >
                        Tabler Icons
                        </div>
                        <div so-on:click="tabIndex=0" class="p-2 m-1 ${
                          this.tabIndex == 1
                            ? "bg-cyan text-bg-cyan"
                            : "bg-warning text-bg-warning"
                        } cursor-pointer">
                        Bootstrap Icons
                        </div>
                    </div>
                    <div class="p-2">
                    <input type="text" so-refs="textSearchEl" class="form-control" placeholder="Search..." so-model="textSearch"  />
                    </div>
                    [list-icon][/list-icon]
                    ${this.footerRender()}
               </div>
        `;
  },
};
