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
  chooseIcon(icon, $targetEl) {
    this.listEl
      .querySelector(".sokeio-active")
      ?.classList.remove("sokeio-active");
    setTimeout(() => {
      $targetEl.classList.add("sokeio-active");
    });
    this.$parent.chooseIcon(icon);
  },
  renderItems() {
    let textSearch = this.$parent.textSearch;
    return (this.$parent.tabIndex == 0 ? bootstrapIcons : tablerIcons)
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
        }" so-on:click="chooseIcon('${
          item.class
        }',$targetEl)"><i class="fs-1  ${item.class}"></i></span>`;
      })
      .join("");
  },
  render() {
    return `<div so-refs="listEl" class="d-flex flex-wrap" style="max-height: calc(100vh - 220px);overflow-y: scroll" >
                ${this.renderItems()}
                </div>`;
  },
};
