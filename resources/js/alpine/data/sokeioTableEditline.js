document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioTableEditline", () => ({
    get dataSelecteds() {
      return this.$wire.dataSelecteds ?? [];
    },
    get tableRowEditline() {
      return this.$wire.tableRowEditline ?? [];
    },
    checkRowEditline($id) {
      if (
        !this.tableRowEditline ||
        Array.isArray(this.tableRowEditline) === false
      ) {
        return false;
      }
      return this.tableRowEditline.includes($id);
    },
    get checkExistEditInSelectd() {
      return (
        this.dataSelecteds.filter((el) =>
          this.checkRowEditline(el)
        ).length > 0
      );
    },
    get checkExistNotEditInSelectd() {
      return (
        this.dataSelecteds.filter(
          (el) => !this.checkRowEditline(el)
        ).length > 0
      );
    },
    sokeioTableEditline($id) {
      this.$wire.tableRowEditline = [
        ...this.tableRowEditline,
        $id,
      ].filter((value, index, self) => self.indexOf(value) === index);
      this.$wire.dataSelecteds = [...this.dataSelecteds, $id].filter(
        (value, index, self) => self.indexOf(value) === index
      );
    },
    cancelSokeioTableEditline($id) {
      this.$wire.tableRowEditline = this.tableRowEditline.filter(
        (el) => el !== $id
      );
      this.$wire.dataSelecteds = this.dataSelecteds.filter(
        (el) => el !== $id
      );
    },
    editRowAllSelected() {
      if (this.dataSelecteds.length > 0) {
        let temps = this.dataSelecteds;
        this.$wire.dataSelecteds = [];
        temps.forEach((id) => {
          this.sokeioTableEditline(id);
        });
      }
    },
    cancelRowAllSelected() {
      if (this.dataSelecteds.length > 0) {
        let temps = this.dataSelecteds;
        temps.forEach((id) => {
          this.cancelSokeioTableEditline(id);
        });
      }
    },
  }));
});
