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
  if ((window as any).sokeioUI.debug===true) {
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
export function executeFn(fn: any, $event: any, app: any) {
  return new Function("$event", "$obj", `with($obj) {  ${fn}}`).apply(app, [
    $event,
    mapObservableProxy(app),
  ]);
}
