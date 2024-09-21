export default {
  checkFirst: () => window.QRCode !== undefined,
  local: {
    js: ["platform/modules/sokeio/easyqrcodejs/dist/easy.qrcode.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup }) => {
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
        ...new Function(`return ${el.getAttribute("wire:qrcode.options")};`)(),
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
    if (el.$sokeio_qrcode) return;
    el.$sokeio_qrcode = new window.QRCode(el, options);
  },
};
