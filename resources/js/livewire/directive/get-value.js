import { dataGet, dataSet } from "../../utils";

export default {
  checkFirst: () => true,
  init: ({ el, directive, component, cleanup }) => {
    let modelKey =
      el.getAttribute("wire:model") || el.getAttribute("wire:get-value.model");
    let varKey = el.getAttribute("wire:get-value");
    let parentId =
      el.getAttribute("wire:get-value.parent") ||
      component.$wire?.soData?.refId;
    console.log({ modelKey, varKey, parentId });
    if (!parentId || !varKey) {
      return;
    }
    let componentParent = window.Livewire.find(parentId);
    if (!componentParent) {
      return;
    }
    let valueVar = dataGet(componentParent, varKey);
    dataSet(component.$wire, modelKey, valueVar);
  },
};
