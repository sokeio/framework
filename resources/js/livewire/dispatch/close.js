export default function (payload) {
  setTimeout(() => {
    document
      .querySelector(`[wire\\:id="${payload.wireId}"]`)
      ?.closest("[data-sokeio-id]")
      ?.__sokeio?.$app?.closeApp?.();
  });
}
