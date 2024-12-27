function mouseX(evt, elWarp) {
  if (evt.pageX) {
    if (document.body.classList.contains("so-modal-open")) {
      return evt.pageX - document.documentElement.scrollLeft;
    }
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

function mouseY(evt, elWarp) {
  if (evt.pageY) {
    if (document.body.classList.contains("so-modal-open")) {
      return evt.pageY - document.documentElement.scrollTop;
    }
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
  state: {
    path: "",
    type: "",
  },
  register() {
    this.$parent.$contextMenu = this;
  },
  itemClick(item) {
    this.$parent.contextMenus[item].action.bind(this.$parent)();
    this.hide();
  },
  itemRender() {
    let html = `
    <div class="so-media-storage-context-menu-item" so-on:click="$parent.refreshData()">
                    <div class="so-media-storage-context-menu-item-icon">
                        <i class="ti ti-refresh"></i>
                    </div>
                    <div class="so-media-storage-context-menu-item-text">Refresh</div>
                </div>
    `;
    // this.$parent.contextMenus
    //   .filter((item) => item.type.includes(this.type))
    //   .forEach((item, key) => {
    //     html += `
    //         <div class="so-media-storage-context-menu-item" so-on:click="itemClick(${key})">
    //                 <div class="so-media-storage-context-menu-item-icon">
    //                     <i class="${item.icon}"></i>
    //                 </div>
    //                 <div class="so-media-storage-context-menu-item-text">${item.title}</div>
    //             </div>

    //         `;
    //   });
    return html;
  },
  open(event, path, type) {
    this.path = path;
    this.type = type;
    window.event.returnValue = false;
    this.refresh();
    let top = 0;
    let left = 0;
    if (this.$el.closest(".so-modal")) {
      let e = this.$parent.$el.getBoundingClientRect();
      top = e.top;
      left = e.left - document.body.scrollLeft;
    }
    this.$el.style.display = "block";
    this.$el.style.top = mouseY(event, this.$app.$el) - top + "px";
    this.$el.style.left = mouseX(event, this.$app.$el) - left + "px";
  },
  hide() {
    this.$el.style.display = "none";
  },
  render() {
    return `
        <div class="so-media-storage-context-menu" so-click-outsite="hide()" style="display:none">
            <div class="so-media-storage-context-menu-header">
                <a href="https://sokeio.com" class="logo-large" target="_blank">  
                    Sokeio FM V1.0
                </a>
                <a href="https://sokeio.com" class="logo-small" target="_blank">
                    SFM1.0
                </a>
            </div>
                ${this.itemRender()}
        </div>
          `;
  },
};
