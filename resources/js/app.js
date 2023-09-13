"use strict";

import { BytePlatform } from "./platform";
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
let ByteManager = new BytePlatform();
window.ByteManager = ByteManager;
window.ByteManager.start();
window.addEventListener("byte::register", function () {
  window.ByteManager.registerPlugin(ConfirmModule);
  window.ByteManager.registerPlugin(FileManagerModule);
  window.ByteManager.registerPlugin(ActionModule);
  window.ByteManager.registerPlugin(LiveWireModule);
  window.ByteManager.registerPlugin(LiveWireChartModule);
  window.ByteManager.registerPlugin(LiveWireFlatpickrModule);
  window.ByteManager.registerPlugin(LiveWireTagifyModule);
  window.ByteManager.registerPlugin(LiveWireSortablejsModule);
  window.ByteManager.registerPlugin(LiveWireCountUpModule);

  window.ByteManager.registerPlugin(ComponentModule);
  window.ByteManager.registerPlugin(ModalModule);
  window.ByteManager.registerPlugin(ToastsModule);

  window.ByteManager.registerPlugin(LiveWireGetValueParentModule);
  window.ByteManager.registerPlugin(LiveWireTinymceModule);
});
window.showToast = function (
  message,
  title = undefined,
  postion = undefined,
  type = undefined,
  messageType = "info"
) {
  window.ByteManager.addMessage(message, messageType, "showToast", {
    title,
    postion,
    type,
  });
};
window.onEventListenerFromDom = onEventListenerFromDom;
window.getShortcodeObjectFromText = getShortcodeObjectFromText;
window.showFileManager = function (callback, type = "file") {
  window.ByteManager.showFileManager(callback, type);
};
window.openShortcodeSetting = function (
  $shortcode,
  $attrs = [],
  $child,
  callback = undefined
) {
  window.ByteManager.openShortcodeSetting($shortcode, $attrs, $child, callback);
};
