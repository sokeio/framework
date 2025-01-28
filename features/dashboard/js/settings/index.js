export default {
  components: {},
  state: {
    $widgets: [],
  },
  register() {
    let self = this;
    this.$wire.getArrayWidgets().then(function (widgets) {
      self.$widgets = widgets;
      console.log(self.$widgets);
    });
  },
  hell() {
    alert("Hello");
  },
  render() {
    return `
        <div class="p-3">
          Xin chào
          <button type="button" class="btn btn-primary" so-on:click="hell()">Click me</button>
        </div>
        `;
  },
};
