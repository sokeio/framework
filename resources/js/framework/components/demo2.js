import Component from "../Component";

export class Demo2 extends Component {
  state = {
    tesst: "<h1>hello world</h1>test",
  };
  render() {
    return `
        <div>
            <h1>hello world</h1>
            <div so-text="tesst">dfdfdfdf</div>
            <div so-html="tesst">dfdfdfdf</div>
            <input so-model="tesst" />
            <select so-model="tesst">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
            <textarea so-model="tesst" ></textarea>
            <button so-on:click="tesst=new Date().getTime();">click</button>
            <button so-on:click="this.tesst=new Date().getTime();">click2</button>
        </div>
        `;
  }
}
