import { logDebug } from "../framework/common/Uitls";

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
  logDebug("getShortcodeObjectFromText", match);
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
export function downloadFile(url, fileName) {
  fetch(url, { method: "get", mode: "no-cors", referrerPolicy: "no-referrer" })
    .then((res) => res.blob())
    .then((res) => {
      const aElement = document.createElement("a");
      aElement.setAttribute("download", fileName);
      const href = URL.createObjectURL(res);
      aElement.href = href;
      aElement.setAttribute("target", "_blank");
      aElement.click();
      URL.revokeObjectURL(href);
    });
}
export function convertDateFormatToMask(dateFormat) {
  let mask = dateFormat.toLowerCase();

  // Thay đổi định dạng ngày
  mask = mask.replace("dd", "99");
  mask = mask.replace("d", "99");

  // Thay đổi định dạng tháng
  mask = mask.replace("mm", "99");
  mask = mask.replace("m", "99");

  // Thay đổi định dạng năm
  mask = mask.replace("yyyy", "9999");
  mask = mask.replace("yy", "9999");
  mask = mask.replace("y", "9999");

  return mask;
}
export function convertTimeFormatToMask(timeFormat) {
  let mask = timeFormat.toLowerCase();

  // Thay đổi định dạng giờ
  mask = mask.replace("hh", "99");
  mask = mask.replace("h", "99");

  // Thay đổi định dạng phút
  mask = mask.replace("ii", "99");
  mask = mask.replace("i", "99");

  // Thay đổi định dạng giây
  mask = mask.replace("ss", "99");
  mask = mask.replace("s", "99");

  return mask;
}
export function convertDateTimeFormatToMask(dateTimeFormat) {
  return convertTimeFormatToMask(convertDateFormatToMask(dateTimeFormat));
}
export function getWireIdFromElement(element) {
  return (
    element.getAttribute("wire:id") ??
    element.closest("[wire:id]").getAttribute("wire:id")
  );
}
export function getWireComponentFromElement(element) {
  return window.Livewire.find(getWireIdFromElement(element));
}
