function mouseX(evt) {
  if (evt.pageX) {
    return evt.pageX;
  } else if (evt.clientX) {
    return (
      evt.clientX +
      (document.documentElement.scrollLeft
        ? document.documentElement.scrollLeft
        : document.body.scrollLeft)
    );
  } else {
    return null;
  }
}

function mouseY(evt) {
  if (evt.pageY) {
    return evt.pageY;
  } else if (evt.clientY) {
    return (
      evt.clientY +
      (document.documentElement.scrollTop
        ? document.documentElement.scrollTop
        : document.body.scrollTop)
    );
  } else {
    return null;
  }
}
export default {
  register() {
    this.$parent.$contextMenu = this;
  },
  open(event, item) {
    this.$el.style.display = "block";
    this.$el.style.top = mouseY(event) + "px";
    this.$el.style.left = mouseX(event) + "px";
    window.event.returnValue = false;
  },
  hide() {
    this.$el.style.display = "none";
  },
  render() {
    return `
        <div class="so-fm-context-menu" so-click-outsite="hide()" style="display:none">
                conext
        </div>
          `;
  },
};
