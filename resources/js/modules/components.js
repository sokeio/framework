import { SokeioPlugin } from "../core/plugin";

export class ComponentModule extends SokeioPlugin {
  getKey() {
    return "BYTE_COMPONENT_MODULE";
  }
  booting() {
    const self = this;
    self
      .getManager()
      .onDocument(
        "click",
        "[byte\\:component]",
        self.clickEventComponent.bind(this)
      );
  }
  openComponent(key, toEl) {
    const self = this;
    if (!toEl) toEl = document?.body;
    self
      .getManager()
      .$axios.post(self.getManager().getUrl("component"), {
        key: key,
      })
      .then(async (response) => {
        if (response.status == 200) {
          let data = response.data;
          if (!data.error_code) {
            let el = self.getManager().htmlToElement(data.html);
            toEl.appendChild(el);
            self.getManager().doTrigger(el);
          } else {
            self.getManager().addError(data.error, "sokeio::component", {
              toEl,
              key,
              data,
            });
          }

          if (data.csrf_token)
            self.getManager().$config["csrf_token"] = data.csrf_token;
        } else {
          let data = await response.data;
          self.getManager().addError(data.error, "sokeio::component", {
            toEl,
            key,
            data,
          });
        }
      });
  }
  clickEventComponent(e) {
    const self = this;
    let elCurrentTarget = e.target;
    let strComponent = elCurrentTarget.getAttribute("byte:component");
    let targetTo = elCurrentTarget.getAttribute("byte:component-target");
    if (targetTo) {
      try {
        targetTo = document.querySelector(targetTo);
      } catch {
        targetTo = undefined;
      }
    }
    if (!targetTo) {
      targetTo = document?.body;
    }
    self.openComponent(strComponent, targetTo);
  }
}
