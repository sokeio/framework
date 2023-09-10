window.addScriptToWindow = function (
  source,
  resolve = null,
  reject = null,
  beforeEl = null,
  async = true,
  defer = true
) {
  try {
    let script = document.createElement("script");
    const prior = beforeEl || document.getElementsByTagName("script")[0];

    script.async = async;
    script.defer = defer;

    function onloadHander(_, isAbort) {
      if (
        isAbort ||
        !script.readyState ||
        /loaded|complete/.test(script.readyState)
      ) {
        script.onload = null;
        script.onreadystatechange = null;
        script = undefined;
        if (resolve) resolve();
      }
    }

    script.onload = onloadHander;
    script.onreadystatechange = onloadHander;
    script.src = source;
    prior.parentNode.insertBefore(script, prior);
  } catch (ex) {}
};
window.addStyleToWindow = function (
  source,
  resolve = null,
  reject = null,
  beforeEl = null,
  async = true,
  defer = true
) {
  try {
    let script = document.createElement("link");
    const prior = beforeEl || document.getElementsByTagName("script")[0];

    script.async = async;
    script.defer = defer;

    function onloadHander(_, isAbort) {
      if (
        isAbort ||
        !script.readyState ||
        /loaded|complete/.test(script.readyState)
      ) {
        script.onload = null;
        script.onreadystatechange = null;
        script = undefined;
        if (resolve) resolve();
      }
    }
    script.rel = "stylesheet";
    script.onload = onloadHander;
    script.onreadystatechange = onloadHander;
    script.href = source;
    prior.parentNode.insertBefore(script, prior);
  } catch (ex) {}
};
window.PlatformLoadScript = function (
  source,
  beforeEl = null,
  async = true,
  defer = true
) {
  if (Array.isArray(source)) {
    var arrSource = source.map(function (item) {
      return window.PlatformLoadScript(item);
    });
    return Promise.all(arrSource)
      .then(function () {
        if (window.ByteManager) {
          window.ByteManager.reloading();
          window.PlatformLoadScript = undefined;
        }
      })
      .catch(function () {
        if (window.ByteManager) {
          window.ByteManager.reloading();
          window.PlatformLoadScript = undefined;
        }
      });
  }
  return new Promise((resolve, reject) => {
    window.addScriptToWindow(source, resolve, reject, beforeEl, async, defer);
  });
};
