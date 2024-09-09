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
function LOG(...args) {
  console.log(...args);
}
export const Utils = {
  getComponentsFromText,
  LOG,
};
