import { Utils } from "../framework/common/Uitls";

document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioField", ($attrModel) => ({
    get FieldValue() {
      return Utils.dataGet(this.$wire, $attrModel);
    },
    set FieldValue(value) {
      Utils.dataSet(this.$wire, $attrModel, value);
    },
  }));
  Alpine.data("sokeioTable", () => ({
    fieldSort: "",
    typeSort: "",
    statusCheckAll: false,
    sortField(el) {
      let field = el.getAttribute("data-field");
      if (field != this.fieldSort) {
        this.fieldSort = field;
        this.typeSort = "asc";
      } else {
        this.typeSort = this.typeSort === "asc" ? "desc" : "asc";
        if (this.typeSort === "asc") {
          this.fieldSort = "";
          this.typeSort = "";
        }
      }
      let orderBy = this.$el.getAttribute("data-sokeio-table-order-by");
      this.$wire.callActionUI(orderBy, {
        field: this.fieldSort,
        type: this.typeSort,
      });
    },
    tableInit() {
      this.$watch("$wire.dataSelecteds", () => {
        let checkedValues = [
          ...this.$el.querySelectorAll(".sokeio-checkbox-one"),
        ].map((el) => el.value);
        this.statusCheckAll =
          checkedValues.length ===
          checkedValues.filter((el) => this.$wire.dataSelecteds.includes(el))
            .length;
      });
      Livewire.hook(
        "request",
        ({ component, commit, respond, succeed, fail }) => {
          succeed(({ snapshot, effect }) => {
            setTimeout(() => {
              let checkedValues = [
                ...this.$el.querySelectorAll(".sokeio-checkbox-one"),
              ].map((el) => el.value);
              this.statusCheckAll =
                checkedValues.length ===
                checkedValues.filter((el) =>
                    this.$wire.dataSelecteds.includes(el)
                ).length;
            }, 0);
          });
        }
      );
    },
    checkboxAll(ev) {
      let isChecked = ev.target.checked;
      let checkedValues = [
        ...this.$el.closest("table").querySelectorAll(".sokeio-checkbox-one"),
      ].map((el) => el.value);
      if (this.$wire.dataSelecteds === undefined) {
        this.$wire.dataSelecteds = [];
      }
      if (isChecked) {
        this.$wire.dataSelecteds =
          this.$wire.dataSelecteds.concat(checkedValues);
      } else {
        this.$wire.dataSelecteds = this.$wire.dataSelecteds.filter(
          (el) => !checkedValues.includes(el)
        );
      }
    },
  }));
});
