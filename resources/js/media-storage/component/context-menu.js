import mediaStorage from "..";
import media from "../../livewire/directive/media";

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
    let itemContext = this.$parent.menuContext[item];
    if (itemContext) {
      if (itemContext.action) {
        let func = new Function(`return function(type,path,item){
            ${itemContext.action}
          }`)();
        func.bind(this.$parent)(this.type, this.path, itemContext);
      }
      if (itemContext.view && this.$parent.views[itemContext.view]) {
        let viewOptions = itemContext.viewOptions || {};
        window.showModal(itemContext.title, {
          template: window.sokeioUI.textScriptToJs(
            this.$parent.views[itemContext.view]
          ),
          data: {
            ...viewOptions,
            item: itemContext,
            path: this.path,
            type: this.type,
            mediaStorage: this.$parent,
          },
        });
      }
    }
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
    let menuContext = this.$parent.menuContext;
    if (menuContext && menuContext.length > 0) {
      menuContext.forEach((item, key) => {
        if (!item.type || !item.type.includes(this.type)) {
          return;
        }
        html += `
            <div class="so-media-storage-context-menu-item" so-on:click="itemClick(${key})">
                    <div class="so-media-storage-context-menu-item-icon">
                        <i class="${item.icon}"></i>
                    </div>
                    <div class="so-media-storage-context-menu-item-text">${item.name}</div>
                </div>
        
            `;
      });
    }
    return html;
  },
  open(event, path, type) {
    this.path = path;
    this.type = type;
    // console.log({ path, type });
    window.event.returnValue = false;
    this.refresh(0);
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
            <div class="so-media-storage-context-menu-header so-logo">
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
