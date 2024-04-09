import { Component } from "../../sokeio/component";

export class EditImage extends Component {
  state = {};
  init() {
    this.onReady(() => {
      
    });
  }
  closeModal() {
    this.destroy();
  }

  render() {
    return `
    <div>
        <div class="fm-modal-overlay" s-on:click="this.closeModal()"></div>
        <div class="fm-modal">
            <div class="fm-content"  style="max-width: 700px;">
                <div class="fm-modal-header">
                    <h3 class="fm-modal-title">Edit Image</h3>
                    <button class="btn-close" s-on:click="this.closeModal()"></button>
                </div>
                <div class="fm-modal-body">
                  
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
