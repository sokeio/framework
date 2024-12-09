import bootstrapIcons from "./bootstrap-icons.js";
import tablerIcons from "./tabler-icons.js";
export default {
  listEl: null,
  boot() {
    this.$parent.listEl = this;
    this.ready(function () {
      this.listEl.scrollTop = this.$parent.listElTop;
    });
  },
  renderItems() {
    let textSearch = this.$parent.textSearch;
    if (this.$parent.tabIndex == 0) {
      return bootstrapIcons
        .filter((item) => {
          return (
            item.name.includes(textSearch) ||
            item.class.includes(textSearch) ||
            textSearch == ""
          );
        })
        .map((item) => {
          return `<span class="p-2 item-icon sokeio-hover rounded-2 ${
            this.icon == item.class ? " sokeio-active" : ""
          }" so-on:click="$parent.chooseIcon('${item.class}')"><i class="fs-1 ${
            item.class
          }"></i></span>`;
        })
        .join("");
    }
    return tablerIcons
      .filter((item) => {
        return (
          item.name.includes(textSearch) ||
          item.class.includes(textSearch) ||
          textSearch == ""
        );
      })
      .map((item) => {
        return `<span class="p-2 item-icon sokeio-hover rounded-2 ${
          this.$parent.icon == item.class ? " sokeio-active" : ""
        }" so-on:click="$parent.chooseIcon('${item.class}')"><i class="fs-1  ${
          item.class
        }"></i></span>`;
      })
      .join("");
  },
  render() {
    return `<div so-refs="listEl" class="d-flex flex-wrap" style="max-height: calc(100vh - 220px);overflow-y: scroll" so-on:scroll="$parent.onScroll($event)">
                ${this.renderItems()}
                </div>`;
  },
};
