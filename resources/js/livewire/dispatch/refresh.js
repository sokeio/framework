export default function (payload) {
  setTimeout(() => {
    console.log(payload);
    window.Livewire.find(payload.wireTargetId)?.soLoadData();
  });
}
