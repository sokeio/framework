export default {
  test() {
    console.log(this);
  },
  render() {
    return `
               <div>
                    DEMO
                    <button so-on:click="test()">Test</button>
               </div>
                `;
  },
};
