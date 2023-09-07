export class FileManagerModule {
  manager = undefined;
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
    this.manager.showFileManager(function (items) {
      self.manager.dataSet(
        component.$wire,
        filemanagerModel,
        items.map((item) => item.url)
      );
    }, filemanagerType);
    return false;
  }
  init() {
    const self = this;
    this.manager.onDocument(
      "click",
      "[byte\\:filemanager]",
      self.clickEvent.bind(self)
    );
  }
  loading() {}
  unint() {}
}
