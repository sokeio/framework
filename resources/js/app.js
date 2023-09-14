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
window.addEventListener("byte::register", function () {
  ByteManager.registerPlugin(ConfirmModule);
  ByteManager.registerPlugin(FileManagerModule);
  ByteManager.registerPlugin(ActionModule);
  ByteManager.registerPlugin(LiveWireModule);
  ByteManager.registerPlugin(LiveWireChartModule);
  ByteManager.registerPlugin(LiveWireFlatpickrModule);
  ByteManager.registerPlugin(LiveWireTagifyModule);
  ByteManager.registerPlugin(LiveWireSortablejsModule);
  ByteManager.registerPlugin(LiveWireCountUpModule);
  ByteManager.registerPlugin(ComponentModule);
  ByteManager.registerPlugin(ModalModule);
  ByteManager.registerPlugin(ToastsModule);
  ByteManager.registerPlugin(LiveWireGetValueParentModule);
  ByteManager.registerPlugin(LiveWireTinymceModule);
});
window.ByteManager = ByteManager;
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
