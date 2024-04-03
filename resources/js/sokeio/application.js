import { Component } from "./component";
import { Props } from "./props";
import { getComponentsFromText } from "./utils";

export class Application extends Component {
  ____components = {};
  ____number = 1000;
  registerComponent(name, component) {
    this.____components[name] = component;
  }
  nextId() {
    return ++this.____number;
  }
  getComponentByName(name, $attrs, componentParent) {
    if (!name || !this.____components[name]) return null;
    let component = this.____components[name].make();
    component.appInstance = this;
    component.getId();
    if (componentParent) {
      component.setParent(componentParent);
    }
    let props = Props.make();
    props.setParent(componentParent);
    props.setCurrent(component);
    props.setAttrs($attrs);
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
  run($selectorOrEl = null) {
    this.appInstance = this;
    this.____number = new Date().getTime();
    this.runComponent();
    if (!$selectorOrEl) {
      $selectorOrEl = document.body;
    }
    if (typeof $selectorOrEl === "string") {
      document.querySelector($selectorOrEl).appendChild(this.appEl);
    } else {
      $selectorOrEl.appendChild(this.appEl);
    }
  }
}
