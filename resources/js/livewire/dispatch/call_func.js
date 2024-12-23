export default function (payload) {
  let func = payload.func;
  let option = payload.option;
  let id = option?.id;
  let params = option?.params ?? [];

  if (id) {
    let component = window.Livewire.find(id);
    if (option?.parent) {
      component = component.$parent;
    }
    if (!component) return;
    component[func](...params);
  }
}
