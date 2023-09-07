export class LiveWireGetValueParentModule {
  manager = undefined;
  init() {}
  loading() {
    const self = this;
    if (window.Livewire && window.tinymce) {
      window.Livewire.directive(
        "get-value",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0) {
            return;
          }
          let modelKey = el.getAttribute("wire:model");
          let varKey = el.getAttribute("wire:get-value");
          let parentId =
            el.getAttribute("wire:get-value-parent") ??
            component.$wire._refComponentId;
          if (!parentId) {
            return;
          }
          let componentParent = window.Livewire.find(parentId);
          if (!componentParent) {
            return;
          }
          let valueVar = self.manager.dataGet(componentParent, varKey);
          self.manager.dataSet(component.$wire, modelKey, valueVar);
        }
      );
    }
  }
  unint() {}
}
