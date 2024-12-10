import footer from "./footer.js";
import list from "./list.js";
export default {
  components: {
    "list-icon": list,
    footer,
  },
  state: {
    tabIndex: 1,
    icon: "",
    textSearch: "",
    selectionStart: null,
    selectionEnd: null,
    listElTop: 0,
    isFirst: true,
  },
  listEl: null,
  footerEl: null,
  textSearchEl: null,
  boot() {
    if (this.isFirst) {
      this.icon = this.$app.iconValue ?? "";
      if (this.icon && !this.icon.includes("ti-")) {
        this.tabIndex = 0;
      }
      this.isFirst = false;
    }

    this.watch("tabIndex", (value) => {
      this.listEl.refresh();
    });
    this.watch("textSearch", (value) => {
      this.listEl.refresh();
    });
    this.onReady(() => {
      setTimeout(() => {
        this.textSearchEl.focus();
      });
    });
  },
  chooseIcon(icon) {
    this.icon = icon;
    this.footerEl.refresh();
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
                    [footer][/footer]
               </div>
        `;
  },
};
