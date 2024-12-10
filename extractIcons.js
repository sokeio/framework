const fs = require("fs");
const path = require("path");

function extractIconsFromCSS(cssFilePath, outputJsonPath, prefix, iconRegex) {
  // Đọc nội dung của file CSS
  fs.readFile(cssFilePath, "utf8", (err, data) => {
    if (err) {
      console.error("Error reading CSS file:", err);
      return;
    }

    const icons = [];
    let match;
    while ((match = iconRegex.exec(data)) !== null) {
      const iconName = match[1];
      const iconClass = `${prefix} ${prefix}-${iconName}`;
      icons.push({ name: iconName, class: iconClass });
    }
    // Xuất ra file JSON
    fs.writeFile(
      outputJsonPath,
      "export default " + JSON.stringify(icons, null, 2) + ";",
      (err) => {
        if (err) {
          console.error("Error writing JSON file:", err);
        } else {
          console.log("Icons extracted successfully to", outputJsonPath);
        }
      }
    );
  });
}

extractIconsFromCSS(
  path.join(__dirname, "public/tabler-icons", "tabler-icons.css"),
  path.join(__dirname, "resources/js/icon", "tabler-icons.js"),
  "ti",
  /\.ti-([a-zA-Z0-9-]+):before\s*/g
);

extractIconsFromCSS(
  path.join(__dirname, "public/bootstrap-icons", "bootstrap-icons.css"),
  path.join(__dirname, "/resources/js/icon", "bootstrap-icons.js"),
  "bi",
  /\.bi-([a-zA-Z0-9-]+)::before\s*/g
);
