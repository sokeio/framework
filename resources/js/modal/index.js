import modal from "./modal";
import * as Utils from "./utils";
window.showModal = function (
  title = "",
  options = {
    url: undefined,
    template: undefined,
    templateId: undefined,
    component: undefined,
    elTarget: undefined,
    data: {},
    callback: () => {},
  }
) {
  if (options.templateId) {
    options.template = document
      .getElementById(options.templateId)
      .innerHTML.replace("export default", "return ");
    delete options.templateId;
    options.component = new Function(options.template)();
    delete options.template;
  }
  if (options.component) {
    let html = Utils.getModalOverlay();
    window.sokeioUI
      .run(
        {
          ...options.component,
          render: function () {
            return Utils.getModalHtmlRender(
              options.component.render?.(),
              options.component.footer?.(),
              options.component.header?.(),
              options.component.icon
            );
          },
        },
        {
          props: { title, ...options },
        }
      )
      .cleanup(function () {
        document.body.removeChild(html);
      });
    return;
  }
  window.sokeioUI.run(modal, { props: { title, ...options } });
};
