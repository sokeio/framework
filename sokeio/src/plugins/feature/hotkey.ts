const mapKey: any = {
  enter: 13,
  esc: 27,
  space: 32,
  tab: 9,
  up: 38,
  down: 40,
  left: 37,
  right: 39,
  a: 65,
  b: 66,
  c: 67,
  d: 68,
  e: 69,
  f: 70,
  g: 71,
  h: 72,
  i: 73,
  j: 74,
  k: 75,
  l: 76,
  m: 77,
  n: 78,
  o: 79,
  p: 80,
  q: 81,
  r: 82,
  s: 83,
  t: 84,
  u: 85,
  v: 86,
  w: 87,
  x: 88,
  y: 89,
  z: 90,
};
const explodeHotkey = (str: string) => {
  // ctrl+alt+shift
  let arr = str.toLowerCase().split("+");
  let shift = arr.includes("shift");
  let ctrl = arr.includes("ctrl");
  let alt = arr.includes("alt");
  let meta =
    arr.includes("meta") || arr.includes("command") || arr.includes("win");
  let keyCodes = arr
    .filter(
      (item) =>
        !["shift", "ctrl", "alt", "meta", "command", "win"].includes(item)
    )
    .map((item) => mapKey[item])
    .filter((item) => item);

  return {
    shift,
    ctrl,
    alt,
    meta,
    keyCodes,
  };
};
export default function ({ component, el, name: _name, method, value }: any) {
  if (value) {
    if (el["__SOKEIO_HOTKEY"]) {
      el.removeEventListener(method, el["__SOKEIO_HOTKEY"]);
    }
    el["__SOKEIO_HOTKEY"] = (e: any) => {
      if (
        el.contains(e.target) ||
        (component.$el && !component.$el.contains(e.target))
      ) {
        return;
      }
      let { shift, ctrl, alt, meta, keyCodes } = explodeHotkey(value);
      if (
        (shift && !e.shiftKey) ||
        (ctrl && !e.ctrlKey) ||
        (alt && !e.altKey) ||
        (meta && !e.metaKey) ||
        (keyCodes.length > 0 && !keyCodes.includes(e.keyCode))
      ) {
        return;
      }
      if (["INPUT", "SELECT", "TEXTAREA"].includes(el.tagName)) {
        el.focus();
        el.dispatchEvent(new Event("focus"));
        e.preventDefault();
        e.stopPropagation && e.stopPropagation();
        return;
      }
      console.log("hotkey:click");
      el.click();
      el.dispatchEvent(new Event("click"));
      e.preventDefault();
      e.stopPropagation && e.stopPropagation();
    };
    window.addEventListener("keydown", el["__SOKEIO_HOTKEY"]);
    component.onDestroy(() => {
      window.removeEventListener("click", el["__SOKEIO_HOTKEY"]);
    });
  }
}
