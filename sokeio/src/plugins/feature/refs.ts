import { getKeyAndComponent } from "../../utils";

export default function ({
  component,
  el,
  name: _name,
  method: _method,
  value,
}: any) {
  if (value) {
    let [$key, $component] = getKeyAndComponent(component, value);
    $component[$key] = el;
  }
}
