export class ComponentModule {
  manager = undefined;
  openComponent(key, toEl) {
    const self = this;
    if (!toEl) toEl = document?.body;
    self.manager.$axios
      .post(self.manager.getUrl("component"), {
        key: key,
      })
      .then(async (response) => {
        if (response.status == 200) {
          let data = response.data;
          if (!data.error_code) {
            let el = self.manager.htmlToElement(data.html);
            toEl.appendChild(el);
            this.manager.doTrigger(el);
          } else {
            this.manager.addError(data.error, "byte::component", {
              toEl,
              key,
              data,
            });
          }

          if (data.csrf_token)
            this.manager.$config["csrf_token"] = data.csrf_token;
        } else {
          let data = await response.data;
          this.manager.addError(data.error, "byte::component", {
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
    this.openComponent(strComponent, targetTo);
  }
  init() {}
  loading() {
    this.manager.onDocument(
      "click",
      "[byte\\:component]",
      this.clickEventComponent.bind(this)
    );
  }
  unint() {}
}
