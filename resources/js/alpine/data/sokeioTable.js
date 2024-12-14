document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioTable", () => ({
    searchExtra: false,
    fieldSort: "",
    typeSort: "",
    statusCheckAll: false,
    get dataSelecteds() {
      return this.$wire.dataSelecteds ?? [];
    },
    editRowAllSelected() {
      if (this.$wire.dataSelecteds.length > 0) {
        let temps = this.$wire.dataSelecteds;
        this.$wire.dataSelecteds = [];
        temps.forEach((id) => {
          this.tableRowEditline(id);
        });
      }
    },
    tableRowEditline($id) {
      this.$wire.tableRowEditline = [...this.$wire.tableRowEditline, $id];
      this.$wire.dataSelecteds = [...this.$wire.dataSelecteds, $id];
    },
    cancelRowEditline($id) {
      this.$wire.tableRowEditline = this.$wire.tableRowEditline.filter(
        (el) => el !== $id
      );
      this.$wire.dataSelecteds = this.$wire.dataSelecteds.filter(
        (el) => el !== $id
      );
    },
    checkRowEditline($id) {
      return this.$wire.tableRowEditline.includes($id);
    },
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
      let orderBy = this.$el
        .closest(".sokeio-table")
        .getAttribute("data-sokeio-table-order-by");
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
