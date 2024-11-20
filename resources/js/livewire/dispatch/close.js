export default function (payload) {
  setTimeout(() => {
    console.log(payload);
    document
      .querySelector(`[wire\\:id="${payload.wireId}"]`)
      ?.closest("[data-sokeio-id]")
      ?.__sokeio?.$app?.onDestroy?.();
  });
}
