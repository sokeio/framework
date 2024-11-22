export default function (payload) {
  setTimeout(() => {
    Alpine.navigate(payload?.url ?? window.location.href);
  });
}
