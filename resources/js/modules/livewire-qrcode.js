import { SokeioPlugin } from "../core/plugin";

export class LiveWireQrCodeModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_QRCODE_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("qrcode", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_qrcode) {
          return;
        }
        let options = {
          titleFont: "normal normal bold 18px Arial", //font. default is "bold 16px Arial"
          titleColor: "#004284", // color. default is "#000"
          titleBackgroundColor: "#ccc", // background color. default is "#fff"
          titleHeight: 40, // height, including subTitle. default is 0
          titleTop: 25, // draws y coordinates. default is 30
        };

        if (el.hasAttribute("wire:qrcode.options")) {
          options = {
            ...options,
            ...new Function(
              `return ${el.getAttribute("wire:qrcode.options")};`
            )(),
          };
        }
        if (
          (options.text == null || options.text == "") &&
          el.getAttribute("wire:qrcode") != ""
        ) {
          options.text = component.$wire[el.getAttribute("wire:qrcode")];
        }
        if (el.hasAttribute("wire:qrcode.text")) {
          options.text = el.getAttribute("wire:qrcode.text");
        }
        if (el.hasAttribute("wire:qrcode.title")) {
          options.title = el.getAttribute("wire:qrcode.title");
        }
        const qrcodeCreate = () => {
          if (el.$wire_qrcode) return;
          el.$wire_qrcode = new window.QRCode(el, options);
        };
        if (window.QRCode) {
          qrcodeCreate();
        } else {
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/easyqrcodejs/dist/easy.qrcode.min.js"
              ),
            function () {
              qrcodeCreate();
            }
          );
        }
      });
    }
  }
}
