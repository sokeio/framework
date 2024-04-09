import { Component } from "../sokeio/component";

export class CtxMenu extends Component {
  state = {};
  closeApp() {
    this.$main.closeApp();
  }
  init() {
    document.addEventListener("click", (e) => {
      this.$el.style.display = "none";
    });
  }
  setEvent(e) {
    this.$el.style.display = "block";
    const menuX = e.clientX + 1;
    const menuY = e.clientY + 1;
    this.$el.style.left = menuX + "px";
    this.$el.style.top = menuY + "px";
  }
  editImage(){
    this.$main.editImage(null);
  }
  render() {
    return `
    <ul class="fm-ctxmenu">
        <li title="JS Functions" class="heading"><span>Actions</span></li>
        <li title="" class="interactive" s-on:click="this.editImage()"><span>Edit Image</span></li>
        <li class="divider"></li><li title="links (<a>)" class="heading"><span>Anchors</span></li>
        <li title="opens in new tab" class="interactive"><a href="https://www.cssscript.com" target="_blank"><span>CSSScript (new tab)</span></a></li>
        <li class="divider"></li><li title="Tooltips are awesome" class="heading"><span>Tooltips</span></li>
        <li title="Disabled items can also have a tooltip" class="disabled"><span>Hover me!</span></li>
        <li class="divider"></li><li title="Properties can also be defined by a tooltip" class="heading"><span>Callbacks</span></li>
        <li title="Disabled items can also have a tooltip" class="disabled"><span>Every property can be defined in a callback</span></li>
        <li title="" class="interactive submenu"><span>Submenus can also be defined in a callback.</span></li>
        <li class="divider"></li>
        <li title="" class="heading"><span>Custom Elements</span></li>
        <li title="" class="disabled submenu"><select style="margin: 2px 20px"><option>Option1</option><option>Option2</option></select></li>
        <li title="" class="disabled submenu"><img src="https://www.jqueryscript.net/favicon.ico" style="margin: 2px 20px; height: 32px;"></li>
        <li class="divider"></li>
        <li title="" class="heading"><span>Styling</span></li>
        <li title="No need to provide a completely custom element" style="font-style: italic; font-weight: normal; text-decoration: underline; transform: skewY(1.5deg); transform-origin: left; color: #ee9900; letter-spacing: 2px; margin-bottom: 10px;" class="heading"><span>Items can be individually styled</span></li>
        <li class="divider"></li><li title="" class="heading"><span>Menuception</span></li>
        <li title="" class="interactive submenu"><span>more ...</span></li>
        <li title="" class="interactive submenu"><span>even more actions</span></li>
        <li class="divider"></li><li title="" class="heading"><span>Event specific stuff</span></li>
        <li title="" class="interactive"><span>Hover me!</span></li>
    </ul>
      `;
  }
}
