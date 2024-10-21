export default function (payload) {
  document
    .querySelector(`[wire\\:id="${payload.wireId}"]`)
    ?.closest("[data-sokeio-id]")
    ?._sokeio?.delete?.();
}
