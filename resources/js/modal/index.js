import modal from "./modal";
import * as Utils from "./utils";
window.showModal = function (
  title = "",
  options = {
    url: undefined,
    template: undefined,
    templateId: undefined,
    elTarget: undefined,
    data: {},
    callback: () => {},
    isHide: false,
    overlay: true,
  }
) {
  let components = options.components || {};
  if (options.templateId) {
    options.template = document
      .getElementById(options.templateId)
      .innerHTML.replace("export default", "return ");
    delete options.templateId;
  }
  if (options.template) {
    let template = options.template;
    if (typeof template === "string") {
      template = new Function(template)();
    }
    components = {
      ...components,
      "sokeio::modal::template": template,
    };
    options = {
      ...options,
      htmlComponent: "<so:sokeio::modal::template></sokeio::modal::template>",
    };
    delete options.template;
  }
  return window.sokeioUI.run(
    { ...modal, overlay: true, focusInput: true },
    {
      props: { title, ...options },
      components,
    }
  );
};
