import { Utils } from "../../framework/common/Uitls";

export default {
  checkFirst: () => true,
  init: ({ el, directive, component, cleanup }) => {
    
    let modelKey = el.getAttribute("wire:model");
    let varKey = el.getAttribute("wire:get-value");
    let parentId =
      el.getAttribute("wire:get-value-parent") ?? component.$wire.soRefId;

    if (!parentId) {
      return;
    }
    let componentParent = window.Livewire.find(parentId);
    if (!componentParent) {
      return;
    }
    let valueVar = Utils.dataGet(componentParent, varKey);
    Utils.dataSet(component.$wire, modelKey, valueVar);
  },
};
