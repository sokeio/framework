import { dataGet, dataSet } from "../../utils";

document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioField", ($attrModel) => ({
    get FieldValue() {
      return dataGet(this.$wire, $attrModel);
    },
    set FieldValue(value) {
      let $attr = $attrModel.split(".");
      if ($attr.length > 1) {
        let $attrParent = $attr.pop();
        let $attrParentModel = $attr.join(".");
        let $attrParentValue = dataGet(this.$wire, $attrParentModel);
        if ($attrParentValue) {
          $attrParentValue[$attrParent] = value;
        }
        dataSet(this.$wire, $attrParentModel, $attrParentValue);
        return;
      }
      dataSet(this.$wire, $attrModel, value);
    },
  }));
});
