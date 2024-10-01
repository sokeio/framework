export default {
  test() {
    alert(123);
  },
  header() {
    return ``;
  },
  footer() {
    return `<button so-on:click="test()">test</button>`;
  },
  render() {
    return `
        <div>
           Chào mọi người abcdđff
        </div>
        `;
  },
};
