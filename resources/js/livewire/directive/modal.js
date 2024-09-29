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
        icon:'<i class="ti ti-alarm"></i>',
    };
    let modalTitle = el.getAttribute("wire:modal.title");
    let eventClick = function () {
      window.showModal(modalTitle, options);
    };
    el.addEventListener("click", eventClick);

    cleanup(() => {
      el.removeEventListener("click", eventClick);
    });
    console.log("init");
  },
};
