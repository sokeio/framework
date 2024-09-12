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
function mapObservableProxy(app) {
  let obj = {};
  Object.keys(app.state).forEach((key) => {
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
export const Utils = {
  getComponentsFromText,
  LOG,
  tagSplit,
  runFunction,
};
