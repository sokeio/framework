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
export function getShortcodeObjectFromText(shortcode) {
  if (!shortcode) return null;
  const pattern = /\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)\](.*?)\[\/\1\]/s;

  // Extract the root shortcode match
  const match = shortcode.match(pattern);

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
    console.log("Shortcode Name:", shortcodeName);
    console.log("Attributes:", attributes);
    console.log("Shortcode Content:", shortcodeContent);
    return { shortcode: shortcodeName, attributes, content: shortcodeContent };
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
