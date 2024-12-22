import { application } from "./application";
import { getComponents, registerComponent } from "./application/common";

const elScriptToJs = (el: any) => {
  let template = el.innerHTML;
  template = template.replace("export default", " return");
  let func = new Function(template);
  return func();
};
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
  getApplication(el: any) {
    if (!el) return null;
    // get component main
    let main = el.querySelector("[sokeio\\:main]");
    if (!main) return null;
    let mainId = main.getAttribute("sokeio:main") ?? "";
    main = elScriptToJs(main);
    // get component with name
    let components = [...el.querySelectorAll("[sokeio\\:component]")];
    let component: any = {};
    components.forEach((el: any) => {
      let name = el.getAttribute("sokeio:component");
      if (!name) return;
      component[name] = elScriptToJs(el);
    });
    let isRun = el.getAttribute("sokeio:run") != null;
    return { mainId, main, components: component, isRun, el };
  },
  getListApplication(el: any) {
    return [...el.querySelectorAll("[sokeio\\:application]")]
      .map((el: any) => {
        return sokeioUI.getApplication(el);
      })
      .filter((app: any) => app);
  },
  execute(application: any, $app = null, $parent = null) {
    let { main, components, isRun, mainId: _mainId, el: elApp } = application;
    if (isRun) {
      let divWrapper = document.createElement("div");
      elApp.parentNode.insertBefore(divWrapper, elApp);
      sokeioUI.run(
        main,
        {
          selector: divWrapper,
          components,
        },
        $app,
        $parent
      );
      elApp.parentNode.removeChild(elApp);
      return true;
    }
    return false;
  },
  runApplication(el: any, $app: any = null, $parent: any = null) {
    sokeioUI.getListApplication(el).forEach((app: any) => {
      if (!sokeioUI.execute(app, $app, $parent)) {
        console.error("Application not run", app);
      }
    });
  },
  runDocument() {
    if (sokeioUI.skipRun) return;
    sokeioUI.runApplication(document.body);
  },
};

export default sokeioUI;
(function () {
  window.sokeioUI = sokeioUI;
})();
document.addEventListener("DOMContentLoaded", function () {
  window.sokeioUI.runDocument();
});
// document.addEventListener(
//   "sokeio::plugin::load",
//   function ({ detail: plugin }: any) {
//     plugin.register({
//       css: [],
//       js: [],
//       excute: function (component: any, event: any) {
//         console.log("plugin_new:excute", event, component);
//       },
//     });
//   }
// );
