import { dataGet, dataSet } from "../../utils";

document.addEventListener("alpine:init", () => {
  Alpine.data("sokeioField", ($attrModel) => ({
    get FieldValue() {
      return dataGet(this.$wire, $attrModel);
    },
    set FieldValue(value) {
      dataSet(this.$wire, $attrModel, value);
    },
  }));
});
