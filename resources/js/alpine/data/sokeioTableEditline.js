document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioTableEditline", () => ({
    sokeioTableEditline($id) {
      this.$wire.tableRowEditline = [
        ...this.$wire.tableRowEditline,
        $id,
      ].filter((value, index, self) => self.indexOf(value) === index);
      this.$wire.dataSelecteds = [...this.$wire.dataSelecteds, $id].filter(
        (value, index, self) => self.indexOf(value) === index
      );
    },
    cancelSokeioTableEditline($id) {
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
        // refresh all page
        this.$nextTick(() => {
          Alpine.$data(document.body).$refresh();
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
