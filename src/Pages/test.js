export default {
  test() {
    alert(123);
  },
  header() {
    return ``;
  },
  footer() {
    return `<button so-on:click="this.test()">test</button>`;
  },
  render() {
    return `
        <div>
            <h1>test</h1>
        </div>
        `;
  },
};
