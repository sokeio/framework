export default {
  components: {},
  state: {
    $widgets: [],
  },
  register() {
    this.loadWidgets();
  },
  loadWidgets() {
    let self = this;
    this.$wire.getArrayWidgets().then(function (widgets) {
      self.$widgets = [...widgets];
      console.log(self.$widgets);
      self.refresh();
    });
  },
  widgetChange($event, $id) {
    let self = this;
    this.$wire
      .widgetChange($id, $event.target.checked)
      .then(function (widgets) {
        self.$widgets = [...widgets];
        setTimeout(function () {
          self.refresh();
        }, 100);
      });
    console.log({ $event: $event, $id: $id, checked: $event.target.checked });
  },
  widgetsRender() {
    let html = "";
    this.$widgets.forEach(function (element) {
      let checked = element.status ? "checked" : "";
      html += `<label class="form-check form-switch form-switch-3">
        <input class="form-check-input" type="checkbox" ${checked} so-on:change="widgetChange($event,'${element.key}')"
        <span class="form-check-label">${element.name}</span>
    </label>`;
    });
    return html;
  },
  render() {
    return `    
        <div class="p-3">    
        ${this.widgetsRender()}
        </div>
        `;
  },
};
