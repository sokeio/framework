import { convertHtmlToElement } from "../utils";
import child from "./child";

export default {
  doRegister: function () {
    this.lifecycle("register");
  },
  doBoot: function () {
    this.lifecycle("boot");
    let html = this.render ? this.render() : "<div></div>";
    html = html.trim();
    if (this.$el) {
      this.$el.innerHTML = html;
    } else {
      this.$el = convertHtmlToElement(html);
    }
    child(this);
    this.tapChildren((item: any) => {
      item.doRegister();
      item.doBoot();
    });
  },
  doReady: function () {
    this.lifecycle("ready");
    this.tapChildren((item: any) => {
      item.doReady();
    });
  },
  doRender: function () {
    if (this.$el) {
      this.$el.setAttribute("data-sokeio-id", this.getId());
      this.$el.__sokeio = this;
    }
    if (this.$parent) {
      let elTemp = this.$parent.$el.querySelector(
        "#sokeio-component-" + this.getId()
      );
      if (!elTemp) return;
      elTemp.replaceWith(this.$el);
    }
    this.lifecycle("render");
    this.tapChildren((item: any) => {
      item.doRender();
    });
  },
  doDestroy: function () {
    this.lifecycle("destroy");
    this.tapChildren((item: any) => {
      item.doDestroy();
    });
    if (this.$parent) {
      this.$parent.$children.splice(this.$parent.$children.indexOf(this), 1);
    }
    this.$el?.remove();
    this.__hooks__.destroy();
    this.$children = [];
    this.$el = null;
    this.state = {};
  },
};
