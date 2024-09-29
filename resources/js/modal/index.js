import modal, { getModalHtmlRender } from "./modal";

window.showModal = function (
  title = "",
  options = {
    url: "",
    template: "",
    templateId: "",
    component: undefined,
    data: {},
    callback: () => {},
  }
) {
  if (options.component) {
    window.sokeioUI.run(
      {
        ...options.component,
        render: function () {
          return getModalHtmlRender(
            options.component.render(),
            options.component.footer?.(),
            options.component.header?.(),
            options.component.icon
          );
        },
      },
      {
        props: { title, ...options },
      }
    );
    return;
  }
console.log('----mdoel');
  window.sokeioUI.run(modal, { props: { title, ...options } });
};
