export default function ({ component, el, name, method, value }) {
  if (value) {
    component.watch(value, () => {
      el.innerHTML = component[value];
    });
    el.innerHTML = component[value];
  }
}
