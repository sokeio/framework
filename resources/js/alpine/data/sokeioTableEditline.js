document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioTableEditline", () => ({
    sokeioTableEditline($id) {
      // check $id is string to number
      if (typeof $id === "string") {
        $id = parseInt($id);
      }
      this.$wire.tableRowEditline = [
        ...this.$wire.tableRowEditline,
        $id,
      ].filter((value, index, self) => self.indexOf(value) === index);
      this.$wire.dataSelecteds = [...this.$wire.dataSelecteds, $id].filter(
        (value, index, self) => self.indexOf(value) === index
      );
    },
    cancelSokeioTableEditline($id) {
      if (typeof $id === "string") {
        $id = parseInt($id);
      }
      this.$wire.tableRowEditline = this.$wire.tableRowEditline.filter(
        (el) => el !== $id
      );
      this.$wire.dataSelecteds = this.$wire.dataSelecteds.filter(
        (el) => el !== $id
      );
    },
    editRowAllSelected() {
      if (this.$wire.dataSelecteds.length > 0) {
        let temps = this.$wire.dataSelecteds;
        temps.forEach((id) => {
          this.sokeioTableEditline(id);
        });
      }
    },
    cancelRowAllSelected() {
      if (this.$wire.dataSelecteds.length > 0) {
        let temps = this.$wire.dataSelecteds;
        this.$wire.dataSelecteds = [];
        temps.forEach((id) => {
          this.cancelSokeioTableEditline(id);
        });
      }
    },
  }));
});
