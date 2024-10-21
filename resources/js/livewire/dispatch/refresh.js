export default function (payload) {
    console.log("refresh", payload);
  window.Livewire.find(payload.wireTargetId)?.soLoadData();
}
