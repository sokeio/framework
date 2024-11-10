document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioBody", () => ({
    isAuth: false,
    authUser: null,
    themeDark: false,
    init() {
      this.$watch("themeDark", (value) => {
        this.$el.setAttribute("data-bs-theme", value ? "dark" : "light");
      });
      document.removeEventListener("sokeio::auth", this.sendAuth.bind(this));
      document.addEventListener("sokeio::auth", this.sendAuth.bind(this));
    },
    sendAuth({ detail: { isAuth, authUser } }) {
      this.isAuth = isAuth;
      this.authUser = authUser;
      console.log(authUser);
    },
    toggleTheme() {
      this.themeDark = !this.themeDark;
    },
  }));
});

window.sokeioGlobal = null;
