import { Sokeio } from "./platform";
import { ComponentModule } from "./modules/components";
import { ActionModule } from "./modules/action";
import { LiveWireModule } from "./modules/livewire";
import { ModalModule } from "./modules/modal";
import { ToastsModule } from "./modules/toasts";
import { ConfirmModule } from "./modules/confirm";
import { LiveWireSortablejsModule } from "./modules/livewire-sortablejs";
import { LiveWireTinymceModule } from "./modules/livewire-tinymce";
import { LiveWireGetValueParentModule } from "./modules/livewire-get-value-parent";
import { LiveWireChartModule } from "./modules/livewire-chart";
import { FileManagerModule } from "./modules/filemanager";
import { getShortcodeObjectFromText, onEventListenerFromDom } from "./utils";
import { LiveWireCountUpModule } from "./modules/livewire-countup";
import { LiveWireTagifyModule } from "./modules/livewire-tagify";
import { LiveWireFlatpickrModule } from "./modules/livewire-flatpickr";
let SokeioManager = new Sokeio();
window.addEventListener("sokeio::register", function () {
  SokeioManager.registerPlugin(ConfirmModule);
  SokeioManager.registerPlugin(FileManagerModule);
  SokeioManager.registerPlugin(ActionModule);
  SokeioManager.registerPlugin(LiveWireModule);
  SokeioManager.registerPlugin(LiveWireChartModule);
  SokeioManager.registerPlugin(LiveWireFlatpickrModule);
  SokeioManager.registerPlugin(LiveWireTagifyModule);
  SokeioManager.registerPlugin(LiveWireSortablejsModule);
  SokeioManager.registerPlugin(LiveWireCountUpModule);
  SokeioManager.registerPlugin(ComponentModule);
  SokeioManager.registerPlugin(ModalModule);
  SokeioManager.registerPlugin(ToastsModule);
  SokeioManager.registerPlugin(LiveWireGetValueParentModule);
  SokeioManager.registerPlugin(LiveWireTinymceModule);
});
window.SokeioManager = SokeioManager;
window.showToast = function (
  message,
  title = undefined,
  postion = undefined,
  type = undefined,
  messageType = "info"
) {
  window.SokeioManager.addMessage(message, messageType, "showToast", {
    title,
    postion,
    type,
  });
};
window.onEventListenerFromDom = onEventListenerFromDom;
window.getShortcodeObjectFromText = getShortcodeObjectFromText;
window.showFileManager = function (callback, type = "file") {
  window.SokeioManager.showFileManager(callback, type);
};
window.openShortcodeSetting = function (
  $editorContainer,
  $shortcode,
  $attrs = [],
  $child,
  callback = undefined
) {
  window.SokeioManager.openShortcodeSetting(
    $editorContainer,
    $shortcode,
    $attrs,
    $child,
    callback
  );
};
