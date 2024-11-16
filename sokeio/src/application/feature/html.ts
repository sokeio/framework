import { getKeyAndComponent } from "../utils";

export default function ({
  component,
  el,
  name: _name,
  method: _method,
  value,
}: any): void {
  if (value) {
    let [$key, $component] = getKeyAndComponent(component, value);
    $component.watch($key, () => {
      el.innerHTML = $component[$key];
    });
    el.innerHTML = $component[$key];
  }
}
