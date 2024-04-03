import { Component } from "./component";
import { Props } from "./props";
import { getComponentsFromText } from "./utils";

export class Application extends Component {
  components = {};
  ____number = 1000;
  registerComponent(name, component) {
    this.components[name] = component;
  }
  nextId() {
    return ++this.____number;
  }
  getComponentByName(name, $attrs, componentParent, isAddChild = true) {
    if (!name || !this.components[name]) return null;
    let component = this.components[name].make();
    component.appInstance = this;
    component.getId();
    if (componentParent) {
      component.setParent(componentParent);
    }
    let props = Props.make();
    props.setParent(componentParent);
    props.setCurrent(component);
    props.setAttrs($attrs);
    if (isAddChild) {
      componentParent.setChild(component);
    }
    return component;
  }
  convertHtmlToElement(html, componentParent) {
    let template = document.createElement("template");
    html = html.trim(); // Never return a text node of whitespace as the result
    let components = getComponentsFromText(html);
    const tagSplit = "############$$$$$$$$############";
    let tempComponents = components.map((item) => {
      html = html.split(item.component).join(tagSplit);
      return this.getComponentByName(item.tag, item.attrs, componentParent);
    });
    if (tempComponents.length) {
      let templHtml = "";
      html.split(tagSplit).forEach((item, index) => {
        templHtml += item;
        if (tempComponents[index]) {
          templHtml +=
            '<span id="component-' +
            tempComponents[index].appComponentId +
            '">' +
            tempComponents[index].appComponentId +
            "</span>";
        }
      });
      html = templHtml;
    }

    template.innerHTML = html;
    componentParent.onReady(() => {
      tempComponents.forEach((item) => {
        if (!item) return;
        let eltemp = componentParent.query("#component-" + item.appComponentId);
        if (eltemp) {
          item.runComponent();
          eltemp.parentNode.insertBefore(item.appEl, eltemp);
          eltemp.remove();
        }
      });
    });
    return template.content.firstChild;
  }
  static run($selectorOrEl = null) {
    let app = this.make();
    app.appInstance = app;
    app.getId();
    app.runComponent();
    if (!$selectorOrEl) {
      $selectorOrEl = document.body;
    }
    if (typeof $selectorOrEl === "string") {
      document.querySelector($selectorOrEl).appendChild(app.appEl);
    } else {
      $selectorOrEl.appendChild(app.appEl);
    }
    return app;
  }
}
