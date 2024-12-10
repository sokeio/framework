export function getToken() {
  if (document.querySelector('meta[name="csrf_token"]')) {
    return document
      .querySelector('meta[name="csrf_token"]')
      ?.getAttribute("value");
  }
  if (document.querySelector('meta[name="csrf-token"]')) {
    return document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("value");
  }
  if (document.querySelector('input[name="_token"]')) {
    return document
      .querySelector('input[name="_token"]')
      ?.getAttribute("value");
  }
  return "";
}
export function logDebug(...args: any[]) {
  if (window.sokeioUI.debug === true) {
    console.log(...args);
  }
}

export function convertHtmlToElement(html: any): any {
  const template = document.createElement("template");
  template.innerHTML = html;
  return template.content.firstChild;
}
export function dispatchEvent(name: string, detail: any) {
  document.dispatchEvent(new CustomEvent(name, { detail }));
}
export function tapChildren(component: any, callback: any) {
  if (component.$children && callback) {
    component.$children.forEach((item: any) => {
      callback(item);
    });
  }
  return component;
}
export function tap(component: any, callback: any) {
  if (component && callback) {
    callback(component);
  }
  return component;
}

export function getMethods(obj: any): any[] {
  const isGetter = (x: any, name: any) =>
    (Object.getOwnPropertyDescriptor(x, name) || {}).get;
  const isFunction = (x: any, name: any) => typeof x[name] === "function";
  const deepFunctions: any = (x: any) =>
    x &&
    x !== Object.prototype &&
    Object.getOwnPropertyNames(x)
      .filter((name) => isGetter(x, name) || isFunction(x, name))
      .concat(deepFunctions(Object.getPrototypeOf(x)) || []);
  const distinctDeepFunctions = (x: any) =>
    Array.from(new Set(deepFunctions(x)));
  const userFunctions = (x: any) =>
    distinctDeepFunctions(x).filter(
      (name: any) => name !== "constructor" && !~name.indexOf("__")
    );
  return userFunctions(obj);
}

export function getKeyAndComponent($component: any, $key: any) {
  let key2 = $key;
  $key.split(".").forEach((key: any) => {
    //check key start $
    if (key.startsWith("$")) {
      if ($component[key] === undefined || $component[key] === null) {
        return;
      }
      $component = $component[key];
    } else {
      key2 = key;
    }
  });
  return [key2, $component];
}

function mapObservableProxy(app: any) {
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
export function executeFn(fn: any, $event: any, el: any, app: any) {
  return new Function(
    "$event",
    "$eventEl",
    "$obj",
    `with($obj) {  ${fn}}`
  ).apply(app, [$event, el, mapObservableProxy(app)]);
}

export function getComponentsFromText(htmlString: string) {
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
export const tagSplit = "############$$$$$$$$############";
export function getModalOverlay() {
  let html = convertHtmlToElement('<div class="so-modal-overlay"></div>');
  let elOverlay = document.body.querySelector(".so-modal-overlay");
  if (elOverlay) {
    elOverlay.parentNode && elOverlay.parentNode.insertBefore(html, elOverlay);
  } else {
    document.body.appendChild(html);
  }
  return html;
}
export function getWireIdFromElement(element: any) {
  if (element.getAttribute("wire:id")) return element.getAttribute("wire:id");
  if (element.closest("[wire\\:id]"))
    return element.closest("[wire\\:id]").getAttribute("wire:id");
  return null;
}

export function addScriptToWindow(source: any) {
  if (!source) {
    return;
  }
  if (!Array.isArray(source)) {
    source = [source];
  }
  const prior = document.getElementsByTagName("script")?.[0];
  let documentHead: any = document.head;
  if (prior && prior.parentNode && prior.parentNode !== documentHead) {
    documentHead = prior.parentNode;
  }
  source.forEach((src: any) => {
    let script = document.createElement("script");
    script.src = src;
    documentHead.insertBefore(script, prior);
  });
}
export function addStyleToWindow(source: any) {
  if (!source) {
    return;
  }
  if (!Array.isArray(source)) {
    source = [source];
  }
  const prior = document.getElementsByTagName("link")[0];
  let documentHead: any = document.head;
  if (prior && prior.parentNode && prior.parentNode !== documentHead) {
    documentHead = prior.parentNode;
  }
  source.forEach((src: any) => {
    let script = document.createElement("link");
    script.rel = "stylesheet";
    script.href = src;
    documentHead.insertBefore(script, prior);
  });
}
