import { SokeioPlugin } from "../core/plugin";

export class LiveWireGetValueParentModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_GET_VALUE_MODULE";
  }
  booting() {
    const self = this;
    if (window.Livewire) {
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
            component.$wire.soRefId;
          if (!parentId) {
            return;
          }
          let componentParent = window.Livewire.find(parentId);
          if (!componentParent) {
            return;
          }
          let valueVar = self.getManager().dataGet(componentParent, varKey);
          self.getManager().dataSet(component.$wire, modelKey, valueVar);
        }
      );
    }
  }
  unint() {}
}
