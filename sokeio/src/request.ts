import { getToken, logDebug } from "./utils";

export function commonFetch(
  url: string,
  body = undefined,
  options = {},
  method = "GET"
) {
  let content = getToken();
  logDebug("fetch::content", content);
  return fetch(url, {
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": content ?? "",
      "X-SOKEIO": "v1.0.0",
      "x-powered-by": "Sokeio Framework",
      "X-Requested-With": "XMLHttpRequest",
    },
    ...options,
    method,
    body: JSON.stringify(body),
  });
}
export default {
  convertJsonToFormData(data: any) {
    let formData = new FormData();
    for (let key in data) {
      if (typeof data[key] === "object") {
        if (JSON.stringify(data[key]) !== "{}") {
          formData.append(key, JSON.stringify(data[key]));
        }
      } else {
        formData.append(key, data[key]);
      }
    }
    return formData;
  },
  fetch(url: string, data = undefined, options = {}, method = "GET") {
    return commonFetch(url, data, options, method);
  },
  get(url: string, data = undefined, options = {}) {
    return this.fetch(url, data, options, "GET");
  },
  post(url: string, data: any = {}, options = {}) {
    return this.fetch(url, data, options, "POST");
  },
  put(url: string, data: any = {}, options = {}) {
    return this.fetch(url, data, options, "PUT");
  },
  delete(url: string, data: any = {}, options = {}) {
    return this.fetch(url, data, options, "DELETE");
  },
  patch(url: string, data: any = {}, options = {}) {
    return this.fetch(url, data, options, "PATCH");
  },
  upload(url: string, dataForm: any, options: any = {}) {
    let headers = options.headers || {};
    let progress = options.progress || false;
    // create request with XMLHttpRequest and use process event and Promise
    // to process response
    return new Promise((resolve, reject) => {
      const xhr: any = new XMLHttpRequest();
      // processData: false,
      xhr.withCredentials = true;

      xhr.open("POST", url);
      Object.entries(headers).forEach(([key, value]: any) => {
        xhr.setRequestHeader(key, value);
      });
      //multipart/form-data
      //'application/x-www-form-urlencoded'
      xhr.setRequestHeader("Accept", "application/json");
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.responseType = "application/json";
      xhr.setRequestHeader("X-SOKEIO", "");
      xhr.setRequestHeader("X-CSRF-TOKEN", getToken() ?? "");
      xhr.upload.addEventListener("progress", (e: any) => {
        progress &&
          progress({
            progress: Math.floor((e.loaded * 100) / e.total),
            loaded: e.loaded,
            total: e.total,
          });
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
