import { logDebug } from "../common/Uitls";

export function getToken() {
  if (document.querySelector('meta[name="csrf_token"]')) {
    return document
      .querySelector('meta[name="csrf_token"]')
      .getAttribute("value");
  }
  if (document.querySelector('meta[name="csrf-token"]')) {
    return document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("value");
  }
  if (document.querySelector('input[name="_token"]')) {
    return document.querySelector('input[name="_token"]').getAttribute("value");
  }
  return "";
}
export function commonFetch(
  url,
  body = undefined,
  options = {},
  method = "GET"
) {
  console.log(url, body, options, method);
  let content = getToken();
  logDebug("fetch::content", content);
  return fetch(url, {
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": content,
      "X-SOKEIO": "",
    },
    ...options,
    method,
    body: JSON.stringify(body),
  });
}
export default {
  fetch(url, data = undefined, options = {}, method = "GET") {
    return commonFetch(url, data, options, method);
  },
  get(url, data = undefined, options = {}) {
    return this.fetch(url, data, options, "GET");
  },
  post(url, data = {}, options = {}) {
    return this.fetch(url, data, options, "POST");
  },
  put(url, data = {}, options = {}) {
    return this.fetch(url, data, options, "PUT");
  },
  delete(url, data = {}, options = {}) {
    return this.fetch(url, data, options, "DELETE");
  },
  patch(url, data = {}, options = {}) {
    return this.fetch(url, data, options, "PATCH");
  },
  upload(url, dataForm, options = {}) {
    let headers = options.headers || {};
    let progress = options.progress || false;
    // create request with XMLHttpRequest and use process event and Promise
    // to process response
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", url);
      Object.entries(headers).forEach(([key, value]) => {
        xhr.setRequestHeader(key, value);
      });
      xhr.responseType = "json";
      xhr.setRequestHeader("X-SOKEIO", "");
      xhr.setRequestHeader("X-CSRF-TOKEN", getToken());
      xhr.upload.addEventListener("progress", (e) => {
        e.detail = {};
        e.detail.progress = Math.floor((e.loaded * 100) / e.total);
        progress && progress(e);
      });
      xhr.onload = () => {
        if (xhr.status === 200) {
          resolve(xhr.response);
        } else {
          reject(xhr.response);
        }
      };
      xhr.onerror = () => {
        reject(xhr.response);
      };
      xhr.send(dataForm);
    });
  },
};
