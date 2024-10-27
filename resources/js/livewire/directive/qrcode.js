export default {
  checkFirst: () => window.QRCode !== undefined,
  local: {
    js: ["/platform/module/sokeio/easyqrcodejs/dist/easy.qrcode.min.js"],
    css: [],
  },
  cdn: {
    js: [],
    css: [],
  },
  init: ({ el, directive, component, cleanup, options }) => {
    if (el.$sokeio_qrcode) return;
    let options2 = {
      titleFont: "normal normal bold 18px Arial", //font. default is "bold 16px Arial"
      titleColor: "#004284", // color. default is "#000"
      titleBackgroundColor: "#ccc", // background color. default is "#fff"
      titleHeight: 40, // height, including subTitle. default is 0
      titleTop: 25, // draws y coordinates. default is 30,

      ...options,
    };

    if (
      (options2.text == null || options2.text == "") &&
      el.getAttribute("wire:qrcode") != ""
    ) {
      options2.text = component.$wire[el.getAttribute("wire:qrcode")];
    }
    if (el.hasAttribute("wire:qrcode.text")) {
      options2.text = el.getAttribute("wire:qrcode.text");
    }
    if (el.hasAttribute("wire:qrcode.title")) {
      options2.title = el.getAttribute("wire:qrcode.title");
    }
    el.$sokeio_qrcode = new window.QRCode(el, options2);
  },
};
