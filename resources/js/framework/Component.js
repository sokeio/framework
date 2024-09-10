import Observable from "./common/Observable";

class Component extends Observable {
  manager;
  parent;
  children = [];
  el;
  renderComponent() {
    let html = this.render();
    this.el.innerHTML = html;
    if (this.el.children.length !== 1) {
      let eltemp = this.el.children[0];
      this.el.parentNode.insertBefore(eltemp, this.el);
      this.el.remove();
      this.el = eltemp;
      
    } else {
      this.el.innerHTML = "[NOT ONE COMPONENT]";
    }
  }
  render() {
    return "";
  }
  run() {
    //
  }
  ready() {}
}

export default Component;
