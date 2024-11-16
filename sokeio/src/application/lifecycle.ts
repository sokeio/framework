import {
  convertHtmlToElement,
  dispatchEvent,
  logDebug,
  tapChildren,
} from "./utils";
import { destroy } from "./common";
import { processChildrenInComponent } from "./component";
import feature from "./feature/_index";
function reRender(component: any) {
  logDebug("component:reRender", component);
  component.reRender && component.reRender();
  tapChildren(component, (item: any) => {
    item.reRender && item.reRender();
  });
  let elParent = component.$el.parentNode;
  let elNext = component.$el.nextSibling;
  component.$el.remove();
  component.$el = null;
  logDebug("reRender", component);
  boot(component);
  render(component);
  ready(component);
  if (elNext) {
    elParent.insertBefore(component.$el, elNext);
  } else {
    elParent.appendChild(component.$el);
  }

  dispatchEvent("sokeio::reRender", { component });
}
function register(component: any) {
  logDebug("component:register", component);
  component.register && component.register();
  tapChildren(component, (item: any) => {
    item.register && item.register();
  });
  dispatchEvent("sokeio::register", { component });
}

function boot(component: any) {
  logDebug("component:boot", component);
  component.boot && component.boot();
  component.__hooks__.fire("boot");
  let html = component.render ? component.render() : "<div></div>";
  html = html.trim();
  if (component.$el) {
    component.$el.innerHTML = html;
  } else {
    component.$el = convertHtmlToElement(html);
  }
  feature(component);
  processChildrenInComponent(component);
  tapChildren(component, (item: any) => {
    item.reRender = () => {
      reRender(item);
    };
    item.register && item.register();
    item.boot && item.boot();
  });
  dispatchEvent("sokeio::boot", { component });
}

function ready(component: any) {
  logDebug("component:ready", component);
  component.ready && component.ready();
  tapChildren(component, (item: any) => {
    item.ready && item.ready();
  });
  dispatchEvent("sokeio::ready", { component });
}

function render(component: any) {
  logDebug("component:render", component);
  component.render && component.render();
  tapChildren(component, (item: any) => {
    item.render && item.render();
    let elTemp = component.$el.querySelector(
      "#sokeio-component-" + item.getId()
    );
    elTemp.parentNode.insertBefore(item.$el, elTemp);
    elTemp.remove();
    render(item);
  });
  if (component.$el) {
    component.$el.setAttribute("data-sokeio-id", component.getId());
    component.$el.__sokeio = component;
  }
  dispatchEvent("sokeio::render", { component });
}

export { register, boot, ready, render, reRender, destroy };
