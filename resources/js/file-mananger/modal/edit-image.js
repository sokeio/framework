import { Component } from "../../sokeio/component";

export class EditImage extends Component {
  state = {};
  editController = null;
  init() {
    this.onReady(() => {
      this.initImageEditor();
    });
  }
  initImageEditor() {
    let imageEditorCreate = () => {
      this.query(".image-editor-wrapper", (el) => {
        const { TABS, TOOLS } = window.FilerobotImageEditor;
        const config = {
          source: this.$props.item.url,
          onSave: (editedImageObject, designState) => {
            this.$props.onSave({
              item: this.$props.item,
              image: editedImageObject,
              design: designState,
            });
          },
          annotationsCommon: {
            fill: "#ff0000",
          },
          Text: { text: "Sokeio Framework..." },
          Rotate: { angle: 90, componentType: "slider" },
          translations: {
            profile: "Profile",
            coverPhoto: "Cover photo",
            facebook: "Facebook",
            socialMedia: "Social Media",
            fbProfileSize: "180x180px",
            fbCoverPhotoSize: "820x312px",
          },
          Crop: {
            presetsItems: [
              {
                titleKey: "classicTv",
                descriptionKey: "4:3",
                ratio: 4 / 3,
                // icon: CropClassicTv, // optional, CropClassicTv is a React Function component. Possible (React Function component, string or HTML Element)
              },
              {
                titleKey: "cinemascope",
                descriptionKey: "21:9",
                ratio: 21 / 9,
                // icon: CropCinemaScope, // optional, CropCinemaScope is a React Function component.  Possible (React Function component, string or HTML Element)
              },
            ],
            presetsFolders: [
              {
                titleKey: "socialMedia", // will be translated into Social Media as backend contains this translation key
                // icon: Social, // optional, Social is a React Function component. Possible (React Function component, string or HTML Element)
                groups: [
                  {
                    titleKey: "facebook",
                    items: [
                      {
                        titleKey: "profile",
                        width: 180,
                        height: 180,
                        descriptionKey: "fbProfileSize",
                      },
                      {
                        titleKey: "coverPhoto",
                        width: 820,
                        height: 312,
                        descriptionKey: "fbCoverPhotoSize",
                      },
                    ],
                  },
                ],
              },
            ],
          },
          tabsIds: [TABS.ADJUST, TABS.ANNOTATE, TABS.WATERMARK], // or ['Adjust', 'Annotate', 'Watermark']
          defaultTabId: TABS.ANNOTATE, // or 'Annotate'
          defaultToolId: TOOLS.TEXT, // or 'Text'
        };

        this.editController = new window.FilerobotImageEditor(el, config);
        this.editController.render();
      });
    };
    if (window.FilerobotImageEditor) {
      console.log("FilerobotImageEditor");
      imageEditorCreate();
    } else {
      console.log("addScriptToWindow");
      window.addScriptToWindow(
        window.SokeioManager.getUrlPublic(
          "platform/modules/sokeio/filerobot-image-editor/filerobot-image-editor.min.js"
        ),
        function () {
          imageEditorCreate();
        }
      );
    }
  }
  closeModal() {
    this.destroy();
  }

  render() {
    return `
    <div>
        <div class="fm-modal-overlay" s-on:click="this.closeModal()"></div>
        <div class="fm-modal fm-modal-fullscreen">
            <div class="fm-content">
                <div class="fm-modal-header">
                    <h3 class="fm-modal-title">Edit Image</h3>
                    <button class="btn-close" s-on:click="this.closeModal()"></button>
                </div>
                <div class="fm-modal-body">
                  <div class="image-editor-wrapper"></div>
                </div>
            </div>
        </div>
    </div>
      `;
  }
}
