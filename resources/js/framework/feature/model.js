export default function ({ component, el, name, method, value }) {
  if (value) {
    component.watch(value, () => {
      el.value = component[value];
    });
    el.value = component[value];

    el.addEventListener("input", (e) => {
      component[value] = e.target.value;
    });
  }
}
