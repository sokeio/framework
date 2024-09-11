import BindHtml from "./bind-html";
import BindModel from "./bind-model";
import BindText from "./bind-text";
import onFeature from "./on";

class FeatureManager {
  chain = [BindText, BindHtml, BindModel, onFeature];
  run(component) {
    this.chain.forEach((feature) => {
      new feature(component).run();
    });
  }
}

export default FeatureManager;
const featureManager = new FeatureManager();

export { featureManager };
