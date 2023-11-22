import { SokeioPlugin } from "../core/plugin";

export class FileManagerModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_FILEMANAGER_MODULE";
  }
  booting() {
    const self = this;
    self.getManager().onDocument(
      "click",
      "[byte\\:filemanager]",
      self.clickEvent.bind(self)
    );
  }
  clickEvent(e) {
    e.stopPropagation && e.stopPropagation();
    e.stopImmediatePropagation && e.stopImmediatePropagation();
    e.preventDefault && e.preventDefault();
    let elCurrentTarget = e.target;
    let parentEl = elCurrentTarget.closest("[wire\\:id]");
    let componentId = parentEl?.getAttribute("wire:id");
    let filemanagerModel = elCurrentTarget?.getAttribute("byte:filemanager");
    let filemanagerType =
      elCurrentTarget?.getAttribute("byte:filemanager-type") || "image";
    let component = window.Livewire.find(componentId)?.__instance;
    let self = this;
    self.getManager().showFileManager(function (items) {
      if (elCurrentTarget?.hasAttribute("byte:filemanager-mutil")) {
        self.getManager().dataSet(
          component.$wire,
          filemanagerModel,
          items.map((item) => item.url)
        );
      } else {
        self.getManager().dataSet(
          component.$wire,
          filemanagerModel,
          items.map((item) => item.url)?.[0]
        );
      }
    }, filemanagerType);
    return false;
  }
}
