import body from "./body";
import header from "./header";
import footer from "./footer";

export default {
  components: {
    "so-fm::header": header,
    "so-fm::body": body,
    "so-fm::footer": footer,
  },
  boot() {
    this.cleanup(function () {});
  },
  render() {
    return ` <div class="so-fm-wrapper">
          [so-fm::header /]
          [so-fm::body /]
          [so-fm::footer /]
        
        </div>`;
  },
};
