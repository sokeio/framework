export default function (payload) {
  setTimeout(() => {
    window.Livewire.find(payload.wireTargetId)?.$parent?.soLoadData();
  });
}
