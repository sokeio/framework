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
    let isFocus = false;
    let isShow = false;
    let getModal = function () {
      let options = {
        icon: '<i class="ti ti-alarm"></i>',
        templateId: "",
        template: "",
        url: "",
        elTarget: el,
        $wire: component.$wire,
        isHide: true,
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
      if (el.hasAttribute("wire:modal.id")) {
        let modalId = el.getAttribute("wire:modal.id");
        let app = sokeioUI.app[modalId];
        if (app) {
          let { main, components, isRun } = app;
          options.template = main;
          options.components = components;
        }
      }
      return window.showModal(modalTitle, options).onDestroy(() => {
        modalInterval = undefined;
        el.modalInstance = undefined;
        isShow = false;
      });
    };
    let eventClick = function () {
      isShow = true;
      isFocus = false;
      if (!el.modalInstance) {
        el.modalInstance = getModal();
      }
      el.modalInstance.show();
    };
    let modalInterval = undefined;
    let modalIntervalLeave = undefined;
    let eventHover = function () {
      if (modalIntervalLeave) {
        clearTimeout(modalIntervalLeave);
        modalIntervalLeave = undefined;
      }
      if (isFocus) {
        return;
      }
      isFocus = true;
      if (modalInterval) {
        return;
      }
      modalInterval = setTimeout(() => {
        el.modalInstance = getModal();
      }, 40);
    };
    let eventLeave = function () {
      modalIntervalLeave = setTimeout(() => {
        modalIntervalLeave = undefined;
        isFocus = false;
        if (modalInterval) {
          clearTimeout(modalInterval);
          modalInterval = undefined;
          if (el.modalInstance && !isShow) {
            el.modalInstance = undefined;
          }
        }
      }, 2000);
    };
    el.addEventListener("click", eventClick);
    el.addEventListener("mouseover", eventHover);
    el.addEventListener("mouseleave", eventLeave);

    cleanup(() => {
      el.removeEventListener("click", eventClick);
      el.removeEventListener("hover", eventHover);
      el.removeEventListener("leave", eventLeave);
    });
  },
};
