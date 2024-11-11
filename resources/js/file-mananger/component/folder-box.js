export default {

  diskRender() {
    let html = ` <select so-model="$parent.disk" class="form-select mt-1">`;

    Object.keys(this.$parent.disks).forEach((key) => {
      html += `<option value="${key}" ${this.$parent.disk == key ? "selected" : ""}>${key}</option>`;
    });
    html += `</select>`;
    return `<div>
    <h2 class="mb-1">Disks</h2>
    ${html}</div>`;
  },
  render() {
    return `
                <div >
                ${this.diskRender()}
                </div>
        `;
  },
};
