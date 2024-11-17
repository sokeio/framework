export function logDebug(...args) {
  if (window.sokeioUI.debug === true) {
    console.log(...args);
  }
}
export function dataSet(object, key, value) {
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
export function dataGet(object, key) {
  if (key === "" || !key) return object;

  return key.split(".").reduce((carry, i) => {
    if (carry === undefined) return undefined;

    return carry[i];
  }, object);
}
export function convertHtmlToElement(html) {
  const template = document.createElement("template");
  template.innerHTML = html;
  return template.content.firstChild;
}
export function addScriptToWindow(source) {
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
}
export function addStyleToWindow(source) {
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
}
