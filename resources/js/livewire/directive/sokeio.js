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
    let template = el.innerHTML;
    template = template.replace("export default", " return");
    let div = document.createElement("div");
    el.parentNode.insertBefore(div, el);
    let func = new Function(template);
    let manager = func();
    window.sokeioUI.run(manager, { selector: div });
    el.style.display = "none";
    // el.parentNode.removeChild(el);
    // cleanup(() => {
    //   div.parentNode.insertBefore(el, div);
    //   div.remove();
    // });
  },
};
