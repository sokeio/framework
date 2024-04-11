import { Component } from "../../sokeio/component";
import { convertBase64ToBlob } from "../../sokeio/utils";

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
              nameOld: this.$props.item.name,
              name: editedImageObject.fullName,
              file: convertBase64ToBlob(editedImageObject.imageBase64),
            });
          },
          annotationsCommon: {
            fill: "#232323",
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
                  {
                    titleKey: "youtube",
                    items: [
                      {
                        titleKey: "channelCover",
                        width: 2560,
                        height: 1440,
                        descriptionKey: "ytChannelCoverSize",
                      },
                      {
                        titleKey: "videoThumbnail",
                        width: 1280,
                        height: 720,
                        descriptionKey: "ytVideoThumbnailSize",
                      },
                    ],
                  },
                  {
                    titleKey: "tiktok",
                    items: [
                      {
                        titleKey: "profile",
                        width: 200,
                        height: 200,
                        descriptionKey: "ttProfileSize",
                      },
                      {
                        titleKey: "videoThumbnail",
                        width: 1000,
                        height: 563,
                        descriptionKey: "ttVideoThumbnailSize",
                      },
                    ],
                  },
                  {
                    titleKey: "instagram",
                    items: [
                      {
                        titleKey: "profile",
                        width: 110,
                        height: 110,
                        descriptionKey: "igProfileSize",
                      },
                      {
                        titleKey: "postImage",
                        width: 1080,
                        height: 1080,
                        descriptionKey: "igPostImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "twitter",
                    items: [
                      {
                        titleKey: "profile",
                        width: 400,
                        height: 400,
                        descriptionKey: "twProfileSize",
                      },
                      {
                        titleKey: "headerPhoto",
                        width: 1500,
                        height: 500,
                        descriptionKey: "twHeaderPhotoSize",
                      },
                    ],
                  },
                  {
                    titleKey: "linkedin",
                    items: [
                      {
                        titleKey: "profile",
                        width: 400,
                        height: 400,
                        descriptionKey: "liProfileSize",
                      },
                      {
                        titleKey: "coverPhoto",
                        width: 1584,
                        height: 396,
                        descriptionKey: "liCoverPhotoSize",
                      },
                    ],
                  },
                  {
                    titleKey: "pinterest",
                    items: [
                      {
                        titleKey: "profile",
                        width: 165,
                        height: 165,
                        descriptionKey: "pinProfileSize",
                      },
                      {
                        titleKey: "pinImage",
                        width: 1000,
                        height: "scalable",
                        descriptionKey: "pinPinImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "snapchat",
                    items: [
                      {
                        titleKey: "profile",
                        width: 320,
                        height: 320,
                        descriptionKey: "scProfileSize",
                      },
                      {
                        titleKey: "snapAd",
                        width: 1080,
                        height: 1920,
                        descriptionKey: "scSnapAdSize",
                      },
                    ],
                  },
                  {
                    titleKey: "reddit",
                    items: [
                      {
                        titleKey: "profile",
                        width: 256,
                        height: 256,
                        descriptionKey: "rdProfileSize",
                      },
                      {
                        titleKey: "postImage",
                        width: 1200,
                        height: 630,
                        descriptionKey: "rdPostImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "tumblr",
                    items: [
                      {
                        titleKey: "profile",
                        width: 128,
                        height: 128,
                        descriptionKey: "tmProfileSize",
                      },
                      {
                        titleKey: "postImage",
                        width: 500,
                        height: "scalable",
                        descriptionKey: "tmPostImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "whatsapp",
                    items: [
                      {
                        titleKey: "profile",
                        width: 640,
                        height: 640,
                        descriptionKey: "waProfileSize",
                      },
                      {
                        titleKey: "sharedImage",
                        width: 800,
                        height: 800,
                        descriptionKey: "waSharedImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "wechat",
                    items: [
                      {
                        titleKey: "profile",
                        width: 200,
                        height: 200,
                        descriptionKey: "wcProfileSize",
                      },
                      {
                        titleKey: "articleImage",
                        width: 900,
                        height: 500,
                        descriptionKey: "wcArticleImageSize",
                      },
                    ],
                  },
                  {
                    titleKey: "line",
                    items: [
                      {
                        titleKey: "profile",
                        width: 300,
                        height: 300,
                        descriptionKey: "lnProfileSize",
                      },
                      {
                        titleKey: "chatSticker",
                        width: 512,
                        height: 512,
                        descriptionKey: "lnChatStickerSize",
                      },
                    ],
                  },
                ],
              },
            ],
          },
          tabsIds: [
            TABS.ADJUST,
            TABS.FILTERS,
            TABS.FINETUNE,
            TABS.RESIZE,
            TABS.ANNOTATE,
            TABS.WATERMARK,
          ], // or ['Adjust', 'Annotate', 'Watermark']
          defaultTabId: TABS.ANNOTATE, // or 'Annotate'
          defaultToolId: TOOLS.TEXT, // or 'Text'
        };

        this.editController = new window.FilerobotImageEditor(el, config);
        this.editController.render();
      });
    };
    if (window.FilerobotImageEditor) {
      imageEditorCreate();
    } else {
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
