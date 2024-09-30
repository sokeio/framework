export default {
  test(){
    alert(123);
  },
  render() {
    return `
        <div>
            <h1>test</h1>
            <button so-on:click="this.test()">test</button>
        </div>
        `;
  },
};
