import modal from "./modal";

window.showModal = function (
  url,
  data = {},
  callback = undefined,
  type = "modal"
) {
  window.sokeioUI.run(modal).showModal(url, data, callback, type);
};
