export default {
  components: {},
  state: {},
  serviceRender() {
    if (!this.$parent.services || this.$parent.services.length < 2) return "";
    let html = '<select class="form-select so-media-storage-service">';

    Object.keys(this.$parent.services).forEach((key) => {
      html += `<option value="${key}">${this.$parent.services[key].name}</option>`;
    });
    html += "</select>";
    return html;
  },
  breadcrumbRender() {
    let html = "";
    let path = this.$parent.path.split("/");
    let curentPath = "";
    for (let i = 0; i < path.length; i++) {
      if (path[i] == "") continue;
      curentPath += "/" + path[i];
      html += `<div class="breadcrumb-item" so-on:click="openFolder('${curentPath}')">${path[i]}</div>`;
    }

    return html;
  },
  openFolder(path) {
    this.$parent.path = path;
  },
  render() {
    return ` <div class="so-media-storage-breadcrumbs">
                ${this.serviceRender()}
                  <div class="breadcrumb">
                  <div class="breadcrumb-item" so-on:click="openFolder('/')">Home</div>
                  ${this.breadcrumbRender()}
                  </div>
                  <div></div>
          </div>`;
  },
};
