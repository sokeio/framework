const fs = require("fs");
const pathSys = require("path");

function getFiles(path, exlcudes, onlyFiles) {
  let files = fs
    .readdirSync(path, { withFileTypes: true })
    .filter(
      (item) =>
        !item.isDirectory() &&
        exlcudes.filter((_i) => item.name.includes(_i)).length == 0 &&
        onlyFiles.filter((_i) => item.name.includes(_i)).length > 0
    )
    .map((item) => pathSys.join(path, item.name));
  fs.readdirSync(path, { withFileTypes: true })
    .filter(
      (item) =>
        item.isDirectory() &&
        exlcudes.filter((_i) => item.name.includes(_i)).length == 0
    )
    .map((item) => pathSys.join(path, item.name))
    .forEach((item) => {
      let _files = getFiles(item, exlcudes, onlyFiles);
      files = [...files, ..._files];
    });
  return files;
}
let filePackage = {
  private: true,
  scripts: {
    dev: "vite",
    build: "vite build",
    preview: "vite preview",
    reinstall: "node ./vendor/sokeio/sokeio/reinstall.js",
    rebuild: "node ./vendor/sokeio/sokeio/rebuild.js",
  },
  devDependencies: {
    vite: "^4.3.9",
    "laravel-vite-plugin2": "^0.7.8",
    "cross-env": "^7.0.3",
    sass: "^1.43.4",
    axios: "^1.3.5"
  },
  dependencies: {},
};
let files2 = getFiles(
  `${process.cwd()}`,
  [
    ".git",
    "node_modules",
    "vendor",
    "storage",
    "database",
    "resources",
    "lang",
    "public",
    "routes",
  ],
  ["package.json"]
).map(function (path) {
  let rawdata = fs.readFileSync(path);
  let dataFile = JSON.parse(rawdata);
  filePackage["devDependencies"] = {
    ...filePackage["devDependencies"],
    ...dataFile["devDependencies"],
  };
  filePackage["dependencies"] = {
    ...filePackage["dependencies"],
    ...dataFile["dependencies"],
  };
  filePackage["scripts"] = {
    ...filePackage["scripts"],
    ...dataFile["scripts"],
  };
});
let data = JSON.stringify(filePackage, null, 4);
fs.writeFileSync(`${process.cwd()}/package.json`, data);

console.log(filePackage);

const { spawn } = require("child_process");

const ls = spawn(`yarn`, [], { cwd: process.cwd() });
ls.stdout.on("data", (data) => {
  console.log(`${data}`);
});

ls.stderr.on("data", (data) => {
  console.log(`${data}`);
});

ls.on("error", (error) => {
  console.error(`${error.message}`);
});

ls.on("close", () => {
  console.log(`done`);
});
