import directive from "./directive/_index";
import dispatch from "./dispatch/_index";
document.addEventListener("livewire:init", () => {
  directive.install(window.Livewire);
});
document.addEventListener("sokeio::dispatch", (event) => {
  dispatch.install(event.detail[0]);
});
