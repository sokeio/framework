import { getKeyAndComponent } from "../common/Uitls";

export default function ({ component, el, name, method, value }) {
  if (value) {
    let [$key, $component] = getKeyAndComponent(component, value);
    $component.watch($key, () => {
      el.value = $component[$key];
    });
    el.value = $component[$key];

    el.addEventListener("input", (e) => {
      $component[$key] = e.target.value;
    });
  }
}
