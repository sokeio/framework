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
    let options = {
      icon: '<i class="ti ti-alarm"></i>',
      templateId: "",
      template: "",
      url: "",
      elTarget: el,
    };
    let modalTitle = el.getAttribute("wire:modal.title");
    if (el.hasAttribute("wire:modal.url")) {
      options.url = el.getAttribute("wire:modal.url");
    }
    if (el.hasAttribute("wire:modal.size")) {
      options.size = el.getAttribute("wire:modal.size");
    }

    if (el.hasAttribute("wire:modal.icon")) {
      options.icon = el.getAttribute("wire:modal.icon");
    }
    if (el.hasAttribute("wire:modal.template-id")) {
      options.templateId = el.getAttribute("wire:modal.template-id");
    }
    if (el.hasAttribute("wire:modal.template")) {
      options.template = el.getAttribute("wire:modal.template");
    }
    let eventClick = function () {
      window.showModal(modalTitle, options);
    };
    el.addEventListener("click", eventClick);

    cleanup(() => {
      el.removeEventListener("click", eventClick);
    });
  },
};
