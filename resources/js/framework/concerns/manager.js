import BindHtml from "./bind-html";
import BindModel from "./bind-model";
import BindText from "./bind-text";
import onEvent from "./on-event";

class FeatureManager {
  chain = [BindText, BindHtml, BindModel, onEvent];
  run(component) {
    this.chain.forEach((feature) => {
      new feature(component).run();
    });
  }
}

export default FeatureManager;
const featureManager = new FeatureManager();

export { featureManager };
