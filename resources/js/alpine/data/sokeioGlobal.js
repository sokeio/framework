document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioGlobal", () => ({
    isAuth: undefined,
    init() {
      window.sokeioGlobal = this;
      this.$watch("$wire.isAuth", (value) => {
        this.sendAuth();
      });
      setTimeout(() => {
        this.sendAuth();
      }, 100);
    },
    sendAuth() {
      if (this.$wire.isAuth != this.isAuth) {
        this.isAuth = this.$wire.isAuth;
        document.dispatchEvent(
          new CustomEvent("sokeio::auth", {
            detail: {
              isAuth: this.$wire.isAuth,
              authUser: this.$wire.authUser,
            },
          })
        );
      }
    },
  }));
});

window.sokeioGlobal = null;
