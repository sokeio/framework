const components: any = {};
export function registerComponent(name: string, component: any) {
  components[name] = component;
}
export function getComponents() {
  return components;
}
