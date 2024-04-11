export function getComponentsFromText(htmlString) {
  const regexComponent =
    /\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;
  return [...htmlString.matchAll(regexComponent)].map((match) => {
    const [component, tag, attrs, , content] = match;
    return {
      component,
      tag,
      attrs,
      content,
    };
  });
}
export function LOG(...args) {
  console.log(...args);
}
export function convertBase64ToBlob(base64Image) {
  // Split into two parts
  const parts = base64Image.split(";base64,");

  // Hold the content type
  const imageType = parts[0].split(":")[1];

  // Decode Base64 string
  const decodedData = window.atob(parts[1]);

  // Create UNIT8ARRAY of size same as row data length
  const uInt8Array = new Uint8Array(decodedData.length);

  // Insert all character code into uInt8Array
  for (let i = 0; i < decodedData.length; ++i) {
    uInt8Array[i] = decodedData.charCodeAt(i);
  }

  // Return BLOB image after conversion
  return new Blob([uInt8Array], { type: imageType });
}
export function copyText(text) {
  return new Promise((resolve, reject) => {
    if (
      typeof navigator !== "undefined" &&
      typeof navigator.clipboard !== "undefined" &&
      navigator.permissions !== "undefined"
    ) {
      const type = "text/plain";
      const blob = new Blob([text], { type });
      const data = [new ClipboardItem({ [type]: blob })];
      navigator.permissions
        .query({ name: "clipboard-write" })
        .then((permission) => {
          if (permission.state === "granted" || permission.state === "prompt") {
            navigator.clipboard.write(data).then(resolve, reject).catch(reject);
          } else {
            reject(new Error("Permission not granted!"));
          }
        });
    } else if (
      document.queryCommandSupported &&
      document.queryCommandSupported("copy")
    ) {
      var textarea = document.createElement("textarea");
      textarea.textContent = text;
      textarea.style.position = "fixed";
      textarea.style.width = "2em";
      textarea.style.height = "2em";
      textarea.style.padding = 0;
      textarea.style.border = "none";
      textarea.style.outline = "none";
      textarea.style.boxShadow = "none";
      textarea.style.background = "transparent";
      document.body.appendChild(textarea);
      textarea.focus();
      textarea.select();
      try {
        document.execCommand("copy");
        document.body.removeChild(textarea);
        resolve();
      } catch (e) {
        document.body.removeChild(textarea);
        reject(e);
      }
    } else {
      reject(
        new Error("None of copying methods are supported by this browser!")
      );
    }
  });
}
