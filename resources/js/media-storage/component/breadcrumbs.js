export default {
  components: {},
  state: {},
  serviceRender() {
    let keys = Object.keys(this.$parent.services);
    if (!this.$parent.services || keys.length < 2) return "";
    let html =
      '<select class="form-select so-media-storage-service" so-on:change="changeService($event.target.value)">';

    keys.forEach((key) => {
      html += `<option value="${key}" ${
        key == this.$parent.service ? "selected" : ""
      }>${this.$parent.services[key].name}</option>`;
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
    this.$parent.refreshData();
  },
  changeService(service) {
    this.$parent.service = service;
    this.$parent.refreshData();
  },
  render() {
    return ` <div class="so-media-storage-breadcrumbs">
                ${this.serviceRender()}
                  <div class="breadcrumb">
                  <div class="breadcrumb-item" so-on:click="openFolder('/')">Home</div>
                  ${this.breadcrumbRender()}
                  </div>
                  <div>
                  <input type="text" class="form-control so-media-storage-search" placeholder="Search..." so-on:input="$parent.searchData($event.target.value)"/>
                  </div>
          </div>`;
  },
};
