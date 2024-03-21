export function onEventListenerFromDom(
  event,
  selector,
  callback,
  dom = undefined
) {
  if (!dom) dom = document;
  dom.addEventListener(event, function (ev) {
    let targetCurrent = ev.target;
    if (targetCurrent.matches(selector)) {
      callback && callback(ev);
    } else if ((targetCurrent = ev.target.closest(selector))) {
      // ev.target = targetCurrent;
      callback && callback({ ...ev, target: targetCurrent });
    }
  });
}
window.shortcodePattern =
  /\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/s;
export function checkShortcode(shortcode) {
  if (!shortcode) return false;
  return window.shortcodePattern.test(shortcode);
}
export function getShortcodeObjectFromText(shortcode) {
  if (!shortcode) return null;

  // Extract the root shortcode match
  const match = shortcode.match(window.shortcodePattern);
  console.log(match);
  if (match) {
    const shortcodeName = match[1];
    const attributesString = match[2];
    const shortcodeContent = match[3];

    // Regular expression pattern to match attribute-value pairs
    const attributePattern = /(\w+)\s*=\s*"([^"]*)"/g;

    // Extract attributes using matchAll()
    const attributeMatches = [...attributesString.matchAll(attributePattern)];

    // Create an object to store attribute-value pairs
    const attributes = {};

    // Iterate over attribute matches and populate the attributes object
    for (const attributeMatch of attributeMatches) {
      const [, attribute, value] = attributeMatch;
      attributes[attribute] = value;
    }
    // Access the extracted shortcode name, attributes, and content
    return {
      shortcode: shortcodeName,
      attributes,
      content: shortcodeContent.trim() == "/]" ? "" : shortcodeContent,
    };
  }
  return null;
}

export function copyText(text) {
  const input = document.createElement("input");
  input.setAttribute("value", text);
  document.body.appendChild(input);
  input.select();
  document.execCommand("copy");
  document.body.removeChild(input);
}
