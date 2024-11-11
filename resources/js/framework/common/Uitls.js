function getComponentsFromText(htmlString) {
  const regexComponent =
    /\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;
  return [...htmlString.matchAll(regexComponent)].map((match) => {
    const [component, tag, attrs, , content] = match;
    return {
      component,
      tag,
      attrs,
      content,
    };
  });
}
const tagSplit = "############$$$$$$$$############";
function LOG(...args) {
  console.log(...args);
}
function getMethods(obj) {
  const isGetter = (x, name) =>
    (Object.getOwnPropertyDescriptor(x, name) || {}).get;
  const isFunction = (x, name) => typeof x[name] === "function";
  const deepFunctions = (x) =>
    x &&
    x !== Object.prototype &&
    Object.getOwnPropertyNames(x)
      .filter((name) => isGetter(x, name) || isFunction(x, name))
      .concat(deepFunctions(Object.getPrototypeOf(x)) || []);
  const distinctDeepFunctions = (x) => Array.from(new Set(deepFunctions(x)));
  const userFunctions = (x) =>
    distinctDeepFunctions(x).filter(
      (name) => name !== "constructor" && !~name.indexOf("__")
    );
  return userFunctions(obj);
}

function mapObservableProxy(app) {
  let obj = {};
  Reflect.ownKeys(app).forEach((key) => {
    // Định nghĩa getter và setter cho thuộc tính value
    Object.defineProperty(obj, key, {
      get() {
        return app[key]; // Trả về giá trị của _value
      },
      set(newValue) {
        app[key] = newValue;
      },
      enumerable: true, // Để thuộc tính có thể được liệt kê
      configurable: true, // Cho phép cấu hình lại thuộc tính
    });
  });
  return obj;
}
function runFunction(txtFunc, $event, app) {
  return new Function("$event", "$obj", `with($obj) {  ${txtFunc}}`).apply(
    app,
    [$event, mapObservableProxy(app)]
  );
}
function dataSet(object, key, value) {
  if (!key || !object) {
    return;
  }
  let segments = key.split(".");

  if (segments.length === 1) {
    object[key] = value;
    return object[key];
  }

  let firstSegment = segments.shift();
  let restOfSegments = segments.join(".");
  if (firstSegment) {
    if (object[firstSegment] === undefined) {
      object[firstSegment] = {};
    }
    this.dataSet(object[firstSegment], restOfSegments, value);
  }
}
function dataGet(object, key) {
  if (key === "" || !key) return object;

  return key.split(".").reduce((carry, i) => {
    if (carry === undefined) return undefined;

    return carry[i];
  }, object);
}
function convertHtmlToElement(html) {
  const template = document.createElement("template");
  template.innerHTML = html;
  return template.content.firstChild;
}
let addScriptToWindow = function (source) {
  if (!source) {
    return;
  }
  if (!Array.isArray(source)) {
    source = [source];
  }
  const prior = document.getElementsByTagName("script")[0];
  source.forEach((src) => {
    let script = document.createElement("script");
    script.src = src;
    prior.parentNode.insertBefore(script, prior);
  });
};
let addStyleToWindow = function (source) {
  if (!source) {
    return;
  }
  if (!Array.isArray(source)) {
    source = [source];
  }
  const prior = document.getElementsByTagName("link")[0];
  source.forEach((src) => {
    let script = document.createElement("link");
    script.rel = "stylesheet";
    script.href = src;
    prior.parentNode.insertBefore(script, prior);
  });
};

export const logDebug = (...args) => {
  if (window.SOKEIO_DEBUG) {
    console.log(...args);
  }
};

export function getModalOverlay() {
  let html = Utils.convertHtmlToElement('<div class="so-modal-overlay"></div>');
  let elOverlay = document.body.querySelector(".so-modal-overlay");
  if (elOverlay) {
    elOverlay.parentNode.insertBefore(html, elOverlay);
  } else {
    document.body.appendChild(html);
  }
  return html;
}
export const Utils = {
  getComponentsFromText,
  LOG,
  tagSplit,
  runFunction,
  getMethods,
  dataSet,
  dataGet,
  convertHtmlToElement,
  addScriptToWindow,
  addStyleToWindow,
};
