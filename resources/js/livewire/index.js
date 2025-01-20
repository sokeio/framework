import directive from "./directive/_index";
import dispatch from "./dispatch/_index";
import { getWireIdFromElement } from "./util";
document.addEventListener("livewire:init", () => {
  directive.install(window.Livewire);
});
document.addEventListener("sokeio::dispatch", (event) => {
  let data = event.detail[0];
  let wireId = getWireIdFromElement(event.target);
  let $wire = window.Livewire.find(wireId);
  dispatch.install({
    type: data.type,
    payload: {
      ...data.payload,
      wireId: wireId,
      $wire,
    },
  });
});
