import * as Utils from "./../../modal/utils";
const messageComponent = {
  state: { html: "", loading: true },
  ready() {},
  render() {
    return Utils.getModalHtmlRender(
      "<div style='min-height:100px' so-html='message'></div>",
      "",
      this.icon
    );
  },
};

export default function (payload) {
  window.sokeioUI.run(messageComponent, { props: { ...payload } });
}
