const fs = require("fs");
const pathSys = require("path");
const vite = require("vite");

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
function getFilesByPaths(paths, exlcudes, onlyFiles) {
  if (Array.isArray(paths)) {
    return [...paths].reduce((prevs, cur) => {
      return [...prevs, ...getFiles(cur, exlcudes, onlyFiles)];
    }, []);
  }
  return getFilesByPaths([paths], exlcudes, onlyFiles);
}

const ModuleLoader = (
  paths = [`${process.cwd()}`],
  exlcudes = [
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
  onlyFiles = ["vite.config"]
) => {
  return getFilesByPaths(paths, exlcudes, onlyFiles).map((item) => {
    item = item.replace(`${process.cwd()}`, ".");
    // return import(item).then((rs) => rs.default);
    return pathSys.dirname(item);
  });
};
function buildVite(root) {
  vite.build({
    root,
    configFile: pathSys.join(root, "vite.config.js"),
    plugins: [],
    esbuild: {
      // target: "commonjs",
      minifyIdentifiers: true,
    },
    build: {
      minify: false,
      commonjsOptions: {},
      chunkSizeWarningLimit: 10000000,
    },
  });
}

ModuleLoader().forEach((item) => {
  buildVite(item + "");
});
