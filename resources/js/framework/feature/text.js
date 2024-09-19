export default function ({ component, el, name, method, value }) {
  if (value) {
    component.watch(value, () => {
      el.innerText = component[value];
    });
    el.innerText = component[value];
  }
}
