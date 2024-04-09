import { Component } from "./component";
import { Props } from "./props";
import { getComponentsFromText } from "./utils";

export class Application extends Component {
  components = {};
  $wire = null;
  $_number = 1000;
  registerComponent(name, component) {
    this.components[name] = component;
  }
  init() {
    this.$_number = new Date().getTime();
  }
  nextId() {
    return ++this.$_number;
  }
  getComponentByName(name, $attrs, componentParent, isAddChild = true) {
    if (!name || !this.components[name]) {
      console.warn({ name, $attrs, componentParent, isAddChild });
      return null;
    }
    let component = this.components[name].make();
    component.$main = this;
    component.getId();
    if (componentParent) {
      component.setParent(componentParent);
    }
    let props = Props.make();
    props.setAttrs($attrs);
    props.setParent(componentParent);
    props.setCurrent(component);
    component.setProps(props);
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
            tempComponents[index].getId() +
            '">' +
            tempComponents[index].getId() +
            "</span>";
        }
      });
      html = templHtml;
    }

    template.innerHTML = html;
    componentParent.onReady(() => {
      tempComponents.forEach((item) => {
        if (!item) return;
        let eltemp = componentParent.query("#component-" + item.getId());
        if (eltemp) {
          item.onMount(() => {
            eltemp.parentNode.insertBefore(item.$el, eltemp);
            eltemp.remove();
          });
          item.runComponent();
        }
      });
    });
    return template.content.firstChild;
  }
  static run($selectorOrEl = null, $attrs = null, $initCallback = null) {
    let app = this.make();
    app.$main = app;
    if ($attrs) {
      app.setProps($attrs);
    }
    if ($initCallback) {
      $initCallback.bind(app);
    }
    app.onReady(() => {
      if ($selectorOrEl && typeof $selectorOrEl === "string") {
        $selectorOrEl = document.querySelector($selectorOrEl);
      }
      if (!$selectorOrEl) {
        $selectorOrEl = document.body;
      }
      if ($selectorOrEl.getAttribute("id")) {
        $selectorOrEl = document.getElementById($selectorOrEl);
      }
      if ($selectorOrEl) {
        $selectorOrEl.appendChild(app.$el);
      }
      $selectorOrEl.appendChild(app.$el);
    });
    app.runComponent();
    return app;
  }
}
