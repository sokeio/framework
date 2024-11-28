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
  init: ({ el, directive, component, cleanup, options }) => {
    if (el.$sokeio_sortable) return;

    el.$sokeio_sortable = window.Sortable.create(el, {
      animation: 150,
      filter: "[data-sortable-ignore]",
      ...options,
      draggable: "[wire\\:sortable\\.item]",
      handle: el.querySelector("[wire\\:sortable\\.handle]")
        ? "[wire\\:sortable\\.handle]"
        : null,
      sort: true,
      dataIdAttr: "data-sortable-id",
      group: {
        animation: 150,
        ...(options?.group ?? {}),
        name: el.getAttribute("wire:sortable"),
        pull: false,
        put: false,
      },
      store: {
        ...(options?.store ?? {}),
        set: function (sortable) {
          try {
            let items = sortable.toArray().map((value, index) => {
              return {
                order: index + 1,
                value: value,
              };
            });
            if (directive.expression) {
              component.$wire.call(directive.expression, items);
            } else {
              Alpine.$data(el).onSortable?.call(el, items);
            }
          } catch (e) {
            console.error(e);
          }
        },
      },
    });
  },
};
