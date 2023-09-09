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
window.byteplatformManager = modulePlatform;
window.byteplatformManager.start();
window.addEventListener("BytePlatformRegister", function () {
  // window.dispatchEvent = document.dispatchEvent;
  // console.log("BytePlatformRegister");
  window.byteplatformManager.register("BYTE_CONFIRM_MODULE", new ConfirmModule());
  window.byteplatformManager.register(
    "BYTE_FILEMANAGER_MODULE",
    new FileManagerModule()
  );
  window.byteplatformManager.register("BYTE_ACTION_MODULE", new ActionModule());
  window.byteplatformManager.register("BYTE_LIVEWIRE_MODULE", new LiveWireModule());
  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_CHART_MODULE",
    new LiveWireChartModule()
  );
  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_FLATPICK_MODULE",
    new LiveWireFlatpickrModule()
  ); 
  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_TAGIFY_MODULE",
    new LiveWireTagifyModule()
  );
  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_SORTABLEJS_MODULE",
    new LiveWireSortablejsModule()
  );
  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_COUNTUP_MODULE",
    new LiveWireCountUpModule()
  );
  
  window.byteplatformManager.register("BYTE_COMPONENT_MODULE", new ComponentModule());
  window.byteplatformManager.register("BYTE_MODAL_MODULE", new ModalModule());
  window.byteplatformManager.register("BYTE_TOASTS_MODULE", new ToastsModule());

  window.byteplatformManager.register(
    "BYTE_LIVEWIRE_GET_VALUE_MODULE",
    new LiveWireGetValueParentModule()
  );
  window.byteplatformManager.register(
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
    window.byteplatformManager.addMessage(message, messageType, "showToast", {
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
  window.byteplatformManager.openShortcodeSetting(
    $shortcode,
    $attrs,
    $child,
    callback
  );
};
