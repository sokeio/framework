export default () => {
  let html = `
  <div class="so-position-manager">
  <div class="so-position so-position-top-left"></div>
    <div class="so-position so-position-top-center"></div>
    <div class="so-position so-position-top-right"></div>
    <div class="so-position so-position-middle-left"></div>
    <div class="so-position so-position-center-center"></div>
    <div class="so-position so-position-middle-right"></div>
    <div class="so-position so-position-bottom-left"></div>
    <div class="so-position so-position-bottom-center"></div>
    <div class="so-position so-position-bottom-right"></div>
  </div>
  `;
  document.body.insertAdjacentHTML("beforeend", html);
};
