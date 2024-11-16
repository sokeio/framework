import { convertHtmlToElement, logDebug, tapChildren } from "./utils";

export function getComponentsFromText(htmlString: string) {
  const regexComponent =
    /\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;
  return [...htmlString.matchAll(regexComponent)].map((match) => {
    const [component, tag, attrs, , content] = match;
    return {
      component,
      tag,
      attrs,
      content,
    };
  });
}
export const tagSplit = "############$$$$$$$$############";
export function getModalOverlay() {
  let html = convertHtmlToElement('<div class="so-modal-overlay"></div>');
  let elOverlay = document.body.querySelector(".so-modal-overlay");
  if (elOverlay) {
    elOverlay.parentNode && elOverlay.parentNode.insertBefore(html, elOverlay);
  } else {
    document.body.appendChild(html);
  }
  return html;
}
let components: any = {};
export function registerComponent(name: string, component: any) {
  components[name] = component;
}
export function getComponents() {
  return components;
}

export function getWireIdFromElement(element: any) {
  if (element.getAttribute("wire:id")) return element.getAttribute("wire:id");
  if (element.closest("[wire\\:id]"))
    return element.closest("[wire\\:id]").getAttribute("wire:id");
  return null;
}

export function destroy(component: any) {
  logDebug("doDestroy", component);
  tapChildren(component, (item: any) => {
    destroy(item);
  });
  // component.destroy && component.destroy();
  component.__hooks__.fire("destroy");
  component.$el?.remove();
  component.__hooks__.destroy();
  component.$children = [];
  component.$el = null;
  component.state = {};
}
