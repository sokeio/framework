import { SokeioPlugin } from "../core/plugin";

export class ActionModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_ACTION_MODULE";
  }
  booting() {
    let self = this;
    self.getManager().onDocument("click", "[sokeio\\:action]", function (e) {
      let elCurrentTarget = e.target;
      if (!elCurrentTarget) return;
      elCurrentTarget.setAttribute("sokeio:action-loading", "");
      let action = elCurrentTarget.getAttribute("sokeio:action");
      let url = elCurrentTarget.getAttribute("sokeio:action-url");
      let method = elCurrentTarget.getAttribute("sokeio:action-method");
      let body = elCurrentTarget.getAttribute("sokeio:action-body");
      let success = elCurrentTarget.getAttribute("sokeio:action-success");
      let error = elCurrentTarget.getAttribute("sokeio:action-error");
      if (action && action != "") {
        //TODO: CALL TO ACTION
        self
          .getManager()
          .$axios.post(self.getManager().getUrl("action"), {
            action,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("sokeio:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("sokeio:action-loading");
          });
      } else if (url && url != "") {
        self
          .getManager()
          .$axios.request({
            method,
            url,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("sokeio:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("sokeio:action-loading");
          });
      }
    });
  }
  unint() {}
}
