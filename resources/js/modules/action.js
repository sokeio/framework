export class ActionModule {
  manager = undefined;
  init() {}
  loading() {
    let selfManager = this;
    this.manager.onDocument("click", "[byte\\:action]", function (e) {
      let elCurrentTarget = e.target;
      elCurrentTarget.setAttribute("byte:action-loading", "");
      let action = elCurrentTarget.getAttribute("byte:action");
      let url = elCurrentTarget.getAttribute("byte:action-url");
      let method = elCurrentTarget.getAttribute("byte:action-method");
      let body = elCurrentTarget.getAttribute("byte:action-body");
      let success = elCurrentTarget.getAttribute("byte:action-success");
      let error = elCurrentTarget.getAttribute("byte:action-error");
      if (action && action != "") {
        //TODO: CALL TO ACTION
        self.manager.$axios
          .post(self.manager.getUrl("action"), {
            action,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("byte:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("byte:action-loading");
          });
      } else if (url && url != "") {
        selfManager.manager.$axios
          .request({
            method,
            url,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("byte:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("byte:action-loading");
          });
      }
    });
  }
  unint() {}
}
