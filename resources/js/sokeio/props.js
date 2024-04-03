import { BaseJS } from "./base";

export class Props extends BaseJS {
  constructor() {
    super();
  }
  ____parent = null;
  ____current = null;
  ____attrs = null;
  setParent($parent) {
    this.____parent = $parent;
    return this;
  }
  setCurrent($current) {
    this.____current = $current;
    return this;
  }
  setAttrs($attrs) {
    this.____attrs = $attrs;
    return this;
  }
}
