import Observable from "./common/Observable";
import { Utils } from "./common/Uitls";
import { featureManager } from "./concerns/manager";

class Component extends Observable {
  number = new Date().getTime();
  nextId() {
    return ++this.number;
  }
  manager;
  parent;
  children = [];
  elApp = null;
  el;
  $id;
  getId() {
    if (!this.$id) {
      this.$id = this.nextId();
    }
    return this.$id;
  }
  applyComponent(html) {
    let template = document.createElement("template");
    html = html.trim(); // Never return a text node of whitespace as the result
    let components = Utils.getComponentsFromText(html);
    let tempComponents = components.map((item) => {
      html = html.split(item.component).join(Utils.tagSplit);
      return this.manager.getComponentByName(item.tag, item.attrs, this);
    });
    if (tempComponents.length) {
      let templHtml = "";
      html.split(Utils.tagSplit).forEach((item, index) => {
        templHtml += item;
        if (tempComponents[index]) {
          templHtml +=
            '<span id="sokeio-component-' +
            tempComponents[index].getId() +
            '">' +
            tempComponents[index].getId() +
            "</span>";
        }
      });
      html = templHtml;
      this.children = tempComponents;
    }

    template.innerHTML = html;
    return template.content.firstChild;
  }
  onEvent(event, callback, selector = null) {
    let arrEl = [this.el];
    if (selector) {
      arrEl = [...this.el.querySelectorAll(selector)];
    }
    if (!arrEl || !arrEl.length) return;
    arrEl.forEach((item) => {
      if (!item) return;
      item.addEventListener(event, callback);
      this.cleanup(() => {
        item.removeEventListener(event, callback);
      });
    });
  }
  renderComponent() {
    let html = this.render();
    if (!this.el) {
      this.el = document.createElement("div");
    }
    if (html) {
      this.el.innerHTML = html;
    }
    if (this.el.children.length) {
      if (html && this.el.parentNode) {
        let eltemp = this.el.children[0];
        this.el.parentNode.insertBefore(eltemp, this.el);
        this.el.remove();
        this.el = eltemp;
      }

      let temp = this.applyComponent(this.el.innerHTML);
      if (temp) {
        this.el = temp;
      }
      this.el.setAttribute("data-sokeio-id", this.getId());
      this.el.__sokeio = this;
      featureManager.run(this);
      this.children.forEach((item) => {
        if (!item) return;
        item.parent = this;
        item.renderComponent();
        let elTemp = this.el.querySelector("#sokeio-component-" + item.getId());
        if (!elTemp) return;
        elTemp.parentNode.insertBefore(item.el, elTemp);
        elTemp.remove();
      });
    } else if (!this.el.innerHTML) {
      this.el.innerHTML = "[NOT ONE COMPONENT]";
    }
    if (this.elApp) {
      this.elApp.appendChild(this.el);
    }
  }
  render() {
    return "";
  }
  boot() {}
  ready() {
    //TODO:
  }
  applyCleanup() {
    this.children.forEach((item) => {
      if (item) {
        item.applyCleanup();
      }
    });
    this.children = [];
    super.applyCleanup();
    this.el.parentNode.removeChild(this.el);
    this.el = null;
    this.parent = null;
    this.$id = null;
    this.children = null;
  }
}

export default Component;
