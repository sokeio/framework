export function MakeObjectProxy($obj) {
  let $proxy = new Proxy($obj, {
    get: (target, property) => {
      if (typeof property === "string") {
        if (typeof $obj[property] === "function") {
          return $obj[property].bind($proxy);
        }
        return $obj.get.bind($obj)(property);
      }
    },
    set: (target, property, value) => {
      if (typeof property === "string") {
        $obj.set.bind($obj)(property, value);
        return true;
      }
      return false;
    },
    has: (target, property) => {
      if (typeof property === "string") {
        return $obj.has.bind($obj)(property);
      }
      return false;
    },
  });
  return $proxy;
}
