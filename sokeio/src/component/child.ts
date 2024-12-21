let processNodeInElement = (component: any, $el: any) => {
  let name = $el.tagName.toLowerCase().split("so:")[1];
  let props: any = {};
  Array.from($el.attributes).forEach((attr: any) => {
    props[attr.name] = attr.value;
  });
  let componentNew = component.getComponent(name, props);
  if (!componentNew) {
    console.error("Component not found: " + name);
    return;
  }
  let elTemp = document.createElement("div");
  elTemp.setAttribute("id", "sokeio-component-" + componentNew.getId());
  $el.replaceWith(elTemp);
  component.$children.push(componentNew);
};
export default (component: any) => {
  console.log(component);
  if (!component.$el) {
    return component;
  }
  // Filter Start "so:"
  Array.from(component.$el.querySelectorAll("*"))
    .filter((item: any) => {
      return (
        item && item.tagName.toLowerCase().startsWith("so:") && !item.__sokeio
      );
    })
    .forEach((item: any) => {
      processNodeInElement(component, item);
    });
  return component;
};
