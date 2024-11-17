export default {
  checkFirst: () => true,
  local: {
    js: [],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
    if (el.$sokeio_template) {
      return;
    }

    let template = el.innerHTML;
    template = template.replace("export default", " return");
    let divWrapper = document.createElement("div");
    divWrapper.setAttribute("data-sokeio-template", new Date().getTime());
    el.parentNode.insertBefore(divWrapper, el);
    let func = new Function(template);
    let manager = func();
    el.$sokeio_template = window.sokeioUI.run(manager, {
      selector: divWrapper,
    });
    divWrapper.parentNode.insertBefore(el.$sokeio_template.$el, divWrapper);
    divWrapper.remove();
    el.setAttribute("data-sokeio-template-id", el.$sokeio_template.getId());
    el.style.display = "none";
    console.log("sokeio", el.$sokeio_template);
    // divWrapper.appendChild(el);
    // el.parentNode.removeChild(el);
    // onDestroy(() => {
    //   div.parentNode.insertBefore(el, div);
    //   div.remove();
    // });
  },
};
