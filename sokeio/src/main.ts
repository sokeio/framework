import { application } from "./application/application";
import { getComponents, registerComponent } from "./application/common";

const sokeioUI: any = {
  version: "1.0.0",
  debug: false,
  skipRun: false,
  run: application,
  regisger: registerComponent,
  getComponents: getComponents,
  get(id: string) {
    if (!id) return null;
    let el = document.querySelector(`[data-sokeio-id="${id}"]`);
    if (!el) return null;

    return (el as any).__sokeio;
  },
  runApplication() {
    if (sokeioUI.skipRun) return;
    document
      .querySelectorAll("[sokeio-application-template]")
      .forEach((el: any) => {
        if (el.$sokeio_template) return;
        if (el.getAttribute("data-sokeio-template-id")) return;
        let template = el.innerHTML;
        template = template.replace("export default", " return");
        let divWrapper = document.createElement("div");
        divWrapper.setAttribute(
          "data-sokeio-template",
          `${new Date().getTime()}`
        );
        el.parentNode.insertBefore(divWrapper, el);
        let func = new Function(template);
        let manager = func();
        el.$sokeio_template = sokeioUI.run(manager, {
          selector: divWrapper,
        });
        if (divWrapper.parentNode) {
          divWrapper.parentNode.insertBefore(
            el.$sokeio_template.$el,
            divWrapper
          );
        }

        divWrapper.remove();
        el.setAttribute("data-sokeio-template-id", el.$sokeio_template.getId());
        el.style.display = "none";
      });
  },
};

export default sokeioUI;
(window as any).sokeioUI = sokeioUI;

document.addEventListener("DOMContentLoaded", sokeioUI.runApplication);
