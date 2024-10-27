export default {
  checkFirst: () => window.Sortable !== undefined,
  local: {
    js: ["/platform/module/sokeio/sortable/sortable.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup,options }) => {
    if (!directive.modifiers.includes("item-group")) {
      return;
    }
    el.$sokeio_sortable_group = window.Sortable.create(el, {
      animation: 150,
      ...options,
      draggable: "[wire\\:sortable-group\\.item]",
      handle: el.querySelector("[wire\\:sortable-group\\.handle]")
        ? "[wire\\:sortable-group\\.handle]"
        : null,
      sort: true,
      dataIdAttr: "wire:sortable-group.item",
      group: {
        ...(options?.group ?? {}),
        name: el
          .closest("[wire\\:sortable-group]")
          .getAttribute("wire:sortable-group"),
        pull: true,
        put: true,
      },
      onSort: () => {
        let masterEl = el.closest("[wire\\:sortable-group]");
        if (!masterEl) return;
        let groups = Array.from(
          masterEl.querySelectorAll("[wire\\:sortable-group\\.item-group]")
        ).map((el, index) => {
          return {
            order: index + 1,
            value: el.getAttribute("wire:sortable-group.item-group"),
            items: el.$sokeio_sortable_group.toArray().map((value, index) => {
              return {
                order: index + 1,
                value: value,
              };
            }),
          };
        });
        component.$wire.call(
          masterEl.getAttribute("wire:sortable-group"),
          groups
        );
      },
    });
  },
};
