"use strict";

import { modulePlatform } from "./platform";
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
window.ByteManager = modulePlatform;
window.ByteManager.start();
window.addEventListener("BytePlatformRegister", function () {
  // window.dispatchEvent = document.dispatchEvent;
  // console.log("BytePlatformRegister");
  window.ByteManager.register("BYTE_CONFIRM_MODULE", new ConfirmModule());
  window.ByteManager.register(
    "BYTE_FILEMANAGER_MODULE",
    new FileManagerModule()
  );
  window.ByteManager.register("BYTE_ACTION_MODULE", new ActionModule());
  window.ByteManager.register("BYTE_LIVEWIRE_MODULE", new LiveWireModule());
  window.ByteManager.register(
    "BYTE_LIVEWIRE_CHART_MODULE",
    new LiveWireChartModule()
  );
  window.ByteManager.register(
    "BYTE_LIVEWIRE_FLATPICK_MODULE",
    new LiveWireFlatpickrModule()
  ); 
  window.ByteManager.register(
    "BYTE_LIVEWIRE_TAGIFY_MODULE",
    new LiveWireTagifyModule()
  );
  window.ByteManager.register(
    "BYTE_LIVEWIRE_SORTABLEJS_MODULE",
    new LiveWireSortablejsModule()
  );
  window.ByteManager.register(
    "BYTE_LIVEWIRE_COUNTUP_MODULE",
    new LiveWireCountUpModule()
  );
  
  window.ByteManager.register("BYTE_COMPONENT_MODULE", new ComponentModule());
  window.ByteManager.register("BYTE_MODAL_MODULE", new ModalModule());
  window.ByteManager.register("BYTE_TOASTS_MODULE", new ToastsModule());

  window.ByteManager.register(
    "BYTE_LIVEWIRE_GET_VALUE_MODULE",
    new LiveWireGetValueParentModule()
  );
  window.ByteManager.register(
    "BYTE_LIVEWIRE_TINYMCE_MODULE",
    new LiveWireTinymceModule()
  );

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
});
window.openShortcodeSetting = function (
  $shortcode,
  $attrs = [],
  $child,
  callback = undefined
) {
  window.ByteManager.openShortcodeSetting(
    $shortcode,
    $attrs,
    $child,
    callback
  );
};
