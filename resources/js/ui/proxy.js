export function MakeObjectProxy($obj) {
  return new Proxy($obj, {
    get: (target, property) => {
      if (typeof property === "string") {
        if (typeof $obj[property] === "function") {
          return $obj[property].bind($obj);
        }
        return $obj.get(property);
      }
    },
    set: (target, property, value) => {
      if (typeof property === "string") {
        $obj.set(property, value);
        return true;
      }
      return false;
    },
  });
}
