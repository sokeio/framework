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

window.SokeioLoadStyle = function (
  source,
  beforeEl = null,
  async = true,
  defer = true
) {
  if (Array.isArray(source)) {
    var arrSource = source.map(function (item) {
      return window.SokeioLoadStyle(item);
    });
    return Promise.all(arrSource);
  }
  return new Promise((resolve, reject) => {
    window.addStyleToWindow(source, resolve, reject, beforeEl, async, defer);
  });
};
window.SokeioLoadScript = function (
  source,
  beforeEl = null,
  async = true,
  defer = true
) {
  if (Array.isArray(source)) {
    var arrSource = source.map(function (item) {
      return window.SokeioLoadScript(item);
    });
    return Promise.all(arrSource);
  }
  return new Promise((resolve, reject) => {
    window.addScriptToWindow(source, resolve, reject, beforeEl, async, defer);
  });
};
window.PlatformLoadScript = function (
  source,
  beforeEl = null,
  async = true,
  defer = true
) {
  const dispatchDocument = (event, details = {}) => {
    document.dispatchEvent(
      new window.Event(event, {
        bubbles: true,
        cancelable: false,
        ...(details ?? {}),
      })
    );
  };
  return window
    .SokeioLoadScript(source, beforeEl, async, defer)
    .then(function () {
      dispatchDocument("sokeio::ready");
      if (window.SokeioManager) {
        window.SokeioManager.start();
        window.PlatformLoadScript = undefined;
      }
    })
    .catch(function () {
      dispatchDocument("sokeio::ready");
      if (window.SokeioManager) {
        window.SokeioManager.start();
        window.PlatformLoadScript = undefined;
      }
    });
};
