export class LiveWireSortablejsModule {
  manager = undefined;
  init() {}
  loading() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("sortable", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.livewire____sortable) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:sortable.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:sortable.options")};`
          )();
        }
        const SortableCreate = () => {
          if (el.livewire____sortable) return;
          el.livewire____sortable = window.Sortable.create(el, {
            animation: 150,
            ...options,
            draggable: "[wire\\:sortable\\.item]",
            handle: el.querySelector("[wire\\:sortable\\.handle]")
              ? "[wire\\:sortable\\.handle]"
              : null,
            sort: true,
            dataIdAttr: "wire:sortable.item",
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
                let items = sortable.toArray().map((value, index) => {
                  return {
                    order: index + 1,
                    value: value,
                  };
                });
                component.$wire.call(directive.expression, items);
              },
            },
          });
        };
        if (window.Sortable) {
          SortableCreate();
        } else {
          window.addScriptToWindow(
            self.manager.getUrlPublic(
              "byteplatform/modules/byteplatform/sortable/sortable.min.js"
            ),
            function () {
              SortableCreate();
            }
          );
        }
      });

      window.Livewire.directive(
        "sortable-group",
        ({ el, directive, component }) => {
          // Only fire this handler on the "root" group directive.
          if (
            !directive.modifiers.includes("item-group") ||
            el.livewire____sortable
          ) {
            return;
          }

          let options = {};

          if (el.hasAttribute("wire:sortable-group.options")) {
            options = new Function(
              `return ${el.getAttribute("wire:sortable-group.options")};`
            )();
          }

          const SortableCreate = () => {
            if (el.livewire____sortable) return;
            el.livewire____sortable = window.Sortable.create(el, {
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
                  masterEl.querySelectorAll(
                    "[wire\\:sortable-group\\.item-group]"
                  )
                ).map((el, index) => {
                  return {
                    order: index + 1,
                    value: el.getAttribute("wire:sortable-group.item-group"),
                    items: el.livewire_sortable
                      .toArray()
                      .map((value, index) => {
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
          };
          if (window.Sortable) {
            SortableCreate();
          } else {
            window.addScriptToWindow(
              self.manager.getUrlPublic(
                "byteplatform/modules/byteplatform/sortable/sortable.min.js"
              ),
              function () {
                SortableCreate();
              }
            );
          }
        }
      );
    }
  }
  unint() {}
}
