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
function runFunction(txtFunc, $event, app) {
  return new Function(
    "$event",
    `
 return  () => {
     ${txtFunc}
     console.log($event);
  }
     `
  ).apply(app, [$event])();
}
export const Utils = {
  getComponentsFromText,
  LOG,
  tagSplit,
  runFunction,
};
