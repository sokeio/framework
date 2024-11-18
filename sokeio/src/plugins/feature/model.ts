import { getKeyAndComponent } from "../../application/utils";

export default function ({
  component,
  el,
  name: _name,
  method: _method,
  value,
}: any) {
  if (value) {
    let [$key, $component] = getKeyAndComponent(component, value);
    $component.watch($key, () => {
      el.value = $component[$key];
    });
    el.value = $component[$key];

    el.addEventListener("input", (e: any) => {
      $component[$key] = e.target.value;
    });
  }
}
