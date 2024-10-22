export default function (payload) {
  window.Livewire.find(payload.wireTargetId)?.soLoadData();
}
