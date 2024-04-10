import { Component } from "../sokeio/component";

export class PropertyInfo extends Component {
  state = {
    currentFile: null,
    currentFolder: null,
  };

  init() {
    this.$main.watch(["currentFile", "currentFolder"], (value, old, p) => {
      if (p === "currentFile") {
        this.currentFolder = null;
      }
      if (p === "currentFolder") {
        this.currentFile = null;
      }
      this.set(p, value);
      this.refreshUI();
    });
    this.onReady(() => {
      this.query(".btn-copy", (el) => {
        if (el) {
          el.addEventListener("click", () => {
            this.copyToClipboard();
          });
        }
      });
    });
  }
  refreshUI() {
    this.runTimeout(
      () => {
        this.runRender();
      },
      "refreshUI",
      100
    );
  }

  isImage() {
    return (
      this.currentFile && /^image\/[a-z]+$/.test(this.currentFile.mime_type)
    );
  }
  copyToClipboard() {
    if (this.currentFile?.url) {
      this.copyText(this.currentFile?.url).then(() => {
        alert("Copied: " + this.currentFile?.url);
      });
    }
  }
  afterRender() {
    let propertyItem = (name, value) => {
      return `<div class="fm-property-item">
        <div class="fm-property-label">${name}:</div>
        <div class="fm-property-value">${value}</div>
      </div>`;
    };
    let propertyItemInput = (name, value) => {
      return `<div class="fm-property-item">
        <div class="fm-property-label">${name}:</div>
        <div class="fm-property-value">
          <div class="input-group">
            <input type="text" class="form-control" readonly value="${value}" >
            <button class="btn btn-primary btn-sm px-2 btn-copy" type="button" style="font-size: 24px;">
              <i class="ti ti-copy"></i>
            </button>
          </div>
        </div>
      </div>`;
    };
    let currentItem = this.currentFile ?? this.currentFolder;
    if (currentItem?.type == "folder") {
      this.query(".fm-property-header", (el) => {
        el.innerHTML = `<i class="ti ti-folder"></i>`;
      });
    } else if (currentItem?.type == "file") {
      if (this.isImage()) {
        let item = document.createElement("img");
        item.src = currentItem.thumb;
        this.query(".fm-property-header", (el) => {
          el.innerHTML = item.outerHTML;
        });
      } else {
        this.query(".fm-property-header", (el) => {
          el.innerHTML = `<i class="ti ti-file"></i>`;
        });
      }
    }
    this.query(".fm-property-body", (el) => {
      el.innerHTML = "";
      // 'ext' => pathinfo($path, PATHINFO_EXTENSION),
      // 'size' => $this->getStorage($disk)->size($path),
      // 'type' => 'file',
      // 'url' => url($path),
      // 'thumb' => url('storage/' . $path),
      if (!currentItem) return;
      el.innerHTML = propertyItem("Name", currentItem.name);
      if (currentItem.type == "file") {
        el.innerHTML += propertyItemInput("URL", currentItem.url);
        el.innerHTML += propertyItem("Type", currentItem.mime_type);
        el.innerHTML += propertyItem("Size", currentItem.size);
        el.innerHTML += propertyItem("Ext", "." + currentItem.ext);
      } else {
        el.innerHTML += propertyItem("File Count", currentItem.file_count);
      }
      el.innerHTML += propertyItem("Modified", currentItem.modified_at);
    });
  }
  render() {
    return `<div class="fm-property-wrapper">
      <div class="fm-property-header">
        <i class="ti ti-file"></i>
      </div>
      <div class="fm-property-body">
         
      </div>
    </div>
      `;
  }
}
