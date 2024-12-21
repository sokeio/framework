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
  getApplicationTemplate(el: any) {
    if (!el) return null;
    // get component main
    let main = el.querySelector("[sokeio\\:component-main]");
    if (!main) return null;
    let mainId = main.getAttribute("sokeio:component-main") ?? "";
    main = elScriptToJs(main);
    // get component with name
    let components = [...el.querySelectorAll("[sokeio\\:component]")];
    let component: any = {};
    components.forEach((el: any) => {
      let name = el.getAttribute("sokeio:component");
      if (!name) return;
      component[name] = elScriptToJs(el);
    });
    let isRun = el.getAttribute("sokeio:application") != null;
    console.log({ mainId, main, components: component, isRun });
    return { mainId, main, components: component, isRun, el };
  },
  getApplicationTemplates(el: any) {
    return [...el.querySelectorAll("[sokeio\\:application-template]")].map(
      (el: any) => {
        return sokeioUI.getApplicationTemplate(el);
      }
    );
  },
  runApplicationTemplate(el: any, $app: any = null, $parent: any = null) {
    sokeioUI.getApplicationTemplates(el).forEach((app: any) => {
      let { main, components, isRun, mainId, el: elApp } = app;
      console.log(mainId);
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
        console.log($app);
      }
      console.log(elApp);
      elApp.parentNode.removeChild(elApp);
    });
  },
  runApplication() {
    if (sokeioUI.skipRun) return;
    sokeioUI.runApplicationTemplate(document.body);
  },
};

export default sokeioUI;
(function () {
  window.sokeioUI = sokeioUI;
})();
document.addEventListener("DOMContentLoaded", function () {
  window.sokeioUI.runApplication();
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
