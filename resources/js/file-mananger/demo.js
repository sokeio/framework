import { Component } from "../sokeio/component";

export class Demo extends Component {
  state = {
    demo: 123,
  };
  init() {
    this.watch("demo", () => {
      console.log("watch demo" + this.demo);
    });
  }
  __time = null;
  demoTest() {
    console.log("demoTest");
    if (this.__time) {
      clearInterval(this.__time);
      this.__time = null;
      return;
    }
    this.__time = setInterval(() => {
      this.demo++;
      this.appParent.demo++;
    }, 1000);
  }
  render() {
    return `
    <div>
      <div> Demo Component 
        <span s-text="demo"></span>
        <button s-on:click="this.demoTest()">Click</button>
      </div>
    </div>
      `;
  }
}
