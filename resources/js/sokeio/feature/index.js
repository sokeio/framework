import { BindFeature } from "./bind";
import { OnFeature } from "./on";

export class FeatureManager {
  component;
  features = [BindFeature, OnFeature];
  objFeatures;
  constructor(component) {
    this.component = component;
    this.objFeatures = this.features.map((feature) => {
      return new feature(this.component);
    });
  }
  runFeatures() {
    this.objFeatures.forEach((feature) => {
      feature.run();
    });
  }
}
