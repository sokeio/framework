document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioPermissionList", () => ({
    changeCheck(event) {
      let isChecked = event.target.checked;
      event.target.parentElement.parentElement
        .querySelectorAll("input")
        .forEach((item) => {
          if (item === event.target) {
            return;
          }
          item.checked = isChecked;
          item.dispatchEvent(new Event("change"));
        });
      setTimeout(() => {
        this.checkGroupPermission();
      }, 100);
    },
    checkGroupPermission() {
      this.$el.querySelectorAll(".permission-group input").forEach((item) => {
        let elPermissions = [
          ...item.parentElement.parentElement.querySelectorAll(
            ".permission-item input"
          ),
        ].filter((permission) => permission != item);
        if (elPermissions.length > 0) {
          item.checked = elPermissions.every(
            (permission) => permission.checked
          );
        } else {
          item.checked = true;
        }
      });
    },
    init() {
      this.$el.querySelectorAll(".permission-item input").forEach((item) => {
        item.addEventListener("change", this.checkGroupPermission.bind(this));
      });
      setTimeout(() => {
        if (!Array.isArray(this.$wire.values)) {
          this.$wire.values = [];
        }
        this.checkGroupPermission();
      });
    },
  }));
});
