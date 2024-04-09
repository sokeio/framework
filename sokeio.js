window.addScriptToWindow = function (
  source,
  resolve = null,
  reject = null,
  beforeEl = null,
  async = true,
  defer = true
) {
  setTimeout(() => {
    let script = document.createElement("script");
    const prior = beforeEl || document.getElementsByTagName("script")[0];

    script.async = async;

    let onResolve = function () {
      resolve && resolve();
    };
    script.onload = onResolve;
    script.onreadystatechange = onResolve;
    script.onerror = onResolve;
    script.src = source;
    prior.parentNode.insertBefore(script, prior);
  }, 0);
};
window.addStyleToWindow = function (
  source,
  resolve = null,
  reject = null,
  beforeEl = null,
  async = true,
  defer = true
) {
  setTimeout(() => {
    let script = document.createElement("link");
    const prior = beforeEl || document.getElementsByTagName("script")[0];

    script.async = async;
    let onResolve = function () {
      resolve && resolve();
    };
    script.rel = "stylesheet";
    script.onload = onResolve;
    script.onreadystatechange = onResolve;
    script.onerror = onResolve;
    script.href = source;
    prior.parentNode.insertBefore(script, prior);
  }, 0);
};

window.SokeioLoadStyle = function (
  source,
  beforeEl = null,
  async = true,
  defer = true
) {
  if (Array.isArray(source)) {
    let arrSource = source.map(function (item) {
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
    let arrSource = source.map(function (item) {
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
      if (window.SokeioManager) {
        window.SokeioManager.start();
        console.log("sokeio::ready");
        dispatchDocument("sokeio::ready");
        window.PlatformLoadScript = undefined;
      }
    })
    .catch(function () {
      if (window.SokeioManager) {
        window.SokeioManager.start();
        console.log("sokeio::ready.2");
        dispatchDocument("sokeio::ready");
        window.PlatformLoadScript = undefined;
      }
    });
};
