import { Utils } from "../framework/common/Uitls";

document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioField", ($attrModel) => ({
    get FieldValue() {
      return Utils.dataGet(this.$wire, $attrModel);
    },
    set FieldValue(value) {
      Utils.dataSet(this.$wire, $attrModel, value);
    },
  }));
});
